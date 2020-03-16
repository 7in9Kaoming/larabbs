<?php

namespace App\Models\Traits;

use App\Models\Topic;
use App\Models\Reply;
use Carbon\Carbon;
use Cache;
use DB;
use Arr;

trait ActiveUserHelper
{
    // 用于存放临时用户数据
    protected $users = [];

    // 配置信息
    protected $topic_weight = 4; // 话题权重
    protected $reply_weight = 1; // 回复权重
    protected $pass_days = 7;    // 多少天内发表过内容
    protected $user_number = 6; // 取出来多少用户

    // 缓存相关配置
    protected $cache_key = 'larabbs_active_users';
    protected $cache_expire_in_seconds = 65 * 60;

    public function getActiveUsers()
    {
        // 尝试从缓存中取出 cache_key 对应的数据。如果能取到，便直接返回数据。
        // 否则运行匿名函数中的代码来取出活跃用户数据，返回的同时做了缓存。
        return Cache::remember($this->cache_key, $this->cache_expire_in_seconds, function(){
            return $this->calculateActiveUsers();
        });
    }

    public function calculateAndCacheActiveUsers()
    {
        // 取得活跃用户列表
        $active_users = $this->calculateActiveUsers();
        // 并加以缓存
        $this->cacheActiveUsers($active_users);
    }

    private function calculateActiveUsers()
    {
        //近期发表话题的用户话题得分
        $users_topic_score_query = DB::table('topics')
            ->select(DB::raw("user_id, count(*)*$this->topic_weight as score"))
            ->where('created_at', '>=', Carbon::now()->subDays($this->pass_days))
            ->groupBy('user_id');

        //近期发表回复的用户回复得分
        $users_reply_score_query = DB::table('replies')
            ->select(DB::raw("user_id, count(*)*$this->reply_weight as score"))
            ->where('created_at', '>=', Carbon::now()->subDays($this->pass_days))
            ->groupBy('user_id');

        //用户的话题和回复得分的联合
        $users_topic_reply_score_query = $users_topic_score_query->unionAll($users_reply_score_query);

        //总得分最高的前6个用户的id
        $users_score_query =  DB::table(DB::raw("({$users_topic_reply_score_query->toSql()}) as users_score"))
            ->selectRaw("user_id, sum(score) as score")
            ->groupBy('user_id')
            ->orderBy('score', 'desc')
            ->limit(6);

        //总得分最高的前6个用户
        $active_users_query = DB::table(DB::raw("({$users_score_query->toSql()}) as users_score"))
            ->join('users', 'users.id', '=', 'users_score.user_id')
            ->select("users.*")
            ->mergeBindings($users_topic_reply_score_query);

        return $active_users_query->get();
    }

    private function cacheActiveUsers($active_users)
    {
        // 将数据放入缓存中
        Cache::put($this->cache_key, $active_users, $this->cache_expire_in_seconds);
    }
}
