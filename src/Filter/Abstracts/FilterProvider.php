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
namespace JYPHP\Core\Filter\Abstracts;

use Illuminate\Support\ServiceProvider;

abstract class FilterProvider extends ServiceProvider
{

    abstract public function register();

    /**
     * 已注册过的过滤器
     * @var array
     */
    private static $filter = [];

    /**
     * 过滤器是否加载
     * @param  $filter
     * @return bool
     */
    public static function has(string $filter): bool
    {
        return (in_array($filter, self::$filter)) ? true : false;
    }

    /**
     * 绑定过滤器
     * @param $abstract
     * @param $concrete
     * @return mixed
     */
    public function bind($abstract, $concrete)
    {
        if (!static::has($abstract))
            self::$filter[] = $abstract;
        return $this->app->bind($abstract, $concrete);
    }
}

