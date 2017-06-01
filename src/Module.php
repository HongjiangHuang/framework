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
namespace JYPHP\Core;

use Illuminate\Support\ServiceProvider;

abstract class Module extends ServiceProvider
{
    public static function description(): string
    {
        return "erp模块";
    }

    public static function name(): string
    {
        return "erp";
    }

    public static function version(): string
    {
        return "1.0 beta";
    }

    public static function author(): string
    {
        return "Albert";
    }
}