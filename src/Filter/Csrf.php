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

class Csrf extends Filter
{

    public function handle(Request $request, \Closure $next, ...$params)
    {
        app()->make("response")->header("Access-Control-Allow-Origin","*");
        app()->make("response")->header("Access-Control-Allow-Methods","POST, GET, OPTIONS,DELETE,PUT");
        return $next($request);
    }
}