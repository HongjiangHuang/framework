<?php
// +----------------------------------------------------------------------
// | PHP [ JUST YOU ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017~2017 http://www.jyphp.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: Albert <albert_p@foxmail.com>
// +----------------------------------------------------------------------
namespace JYPHP\Core\Filter;

use JYPHP\Core\Filter\Abstracts\Filter;
use JYPHP\Core\Http\Request;

final class Csrf extends Filter
{

    public function handle(Request $request, \Closure $next, ...$params)
    {
        if (in_array($_SERVER["HTTP_ORIGIN"], config("api.http_origin", []))) {
            app()->make("response")->header("Access-Control-Allow-Origin", $_SERVER["HTTP_ORIGIN"]);
            app()->make("response")->header("Access-Control-Allow-Methods", "POST,GET,OPTIONS,DELETE,PUT");
        }
        return $next($request);
    }
}