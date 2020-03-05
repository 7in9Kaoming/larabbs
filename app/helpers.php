<?php

function route_class()
{
	return str_replace('.', '-', Route::currentRouteName());
}

// 设置导航的active状态
function category_nav_active($category_id)
{
    return active_class((if_route('categories.show') && if_route_param('category', $category_id)));
}

/**
 * 生成文章摘要
 * @param  string  $value  文章的内容
 * @param  integer $length 摘要的长度
 * @return string          生成的摘要
 */
function make_excerpt($value, $length = 200)
{
    $excerpt = trim(preg_replace('/\r\n|\r|\n+/', ' ', strip_tags($value)));
    return Str::limit($excerpt, $length);
}
