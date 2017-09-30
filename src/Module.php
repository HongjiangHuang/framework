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
    abstract public function boot();
    abstract public function register();
    /**
     * 模块描述
     * @return string
     */
    abstract public static function description(): string;

    /**
     * 模块名
     * @return string
     */
    abstract public static function name(): string;

    /**
     * 模块版本
     * @return string
     */
    abstract public static function version(): string;

    /**
     * 模块作者
     * @return string
     */
    abstract public static function author(): string;
}