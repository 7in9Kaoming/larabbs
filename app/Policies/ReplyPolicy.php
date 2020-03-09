<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Reply;

class ReplyPolicy extends Policy
{
    public function update(User $user, Reply $reply)
    {
        return $reply->user_id == $user->id;
    }

    public function destroy(User $user, Reply $reply)
    {
        // 用户是否拥有本条评论或用户是否是评论的文章的作者
        return $user->isAuthorOf($reply) || $user->isAuthorOf($reply->topic);
    }
}
