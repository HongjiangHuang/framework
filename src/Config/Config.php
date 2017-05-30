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
declare(strict_types = 1);
namespace JYPHP\Core\Config;

use Illuminate\Support\Arr;

/**
 * 配置管理类
 * @package JYPHP\Core\Config
 */
class Config
{
    /**
     * 配置
     * @var array
     */
    protected static $config = [];

    /**
     * 配置命名空间
     * @var string
     */
    protected static $namespace = "app";

    public static function setNamespace(?string $namespace = null)
    {
        if ($namespace !== null){
            self::$namespace = $namespace;
        }
    }

    /**
     * @param array $config
     * @param $namespace
     * @return array
     */
    public static function load(array $config = [] , $namespace = null) : array
    {
        //self::setNamespace($namespace);
        return self::$config[$namespace] =
            array_merge(
                (self::$config[$namespace] ?: [])
                ,
                $config
            );
    }


    /**
     * 验证
     * @param string $key
     * @return bool
     */
    public static function has(string $key): bool
    {
        return Arr::has((self::$config[self::$namespace] ?: []), $key);
    }

    /**
     * 获取配置
     * @param string $key
     * @param string $default
     * @return string
     */
    public static function get(string $key, ?string $default = "")
    {
        return (self::$config[self::$namespace] ?: [])[$key] ?: $default;
    }

    /**
     * 获取整个命名空间下的配置
     * @param null|string|null $namespace
     * @return array
     */
    public static function getNamespace(?string $namespace = null) : array
    {
        self::setNamespace($namespace);
        return self::$config[self::$namespace]?:[];
    }
}