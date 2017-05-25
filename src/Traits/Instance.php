<?php
// +----------------------------------------------------------------------
// | JYPHP [ JY PHP Framework ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017~2017 http://www.jyphp.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: Albert <albert_p@foxmail.com>
// +----------------------------------------------------------------------
namespace JYPHP\Core\Traits;

trait Instance
{
    protected static $instance;

    /**
     * 生成实例
     * @param null $_arg
     * @return static
     */
    public static function instance($_arg = null)
    {
        $params = func_get_args();
        if(empty(static::$instance))
            static::$instance = new static(...$params);
        return static::$instance;
    }

    /**
     * 重新生成实例
     * @param null $_arg
     * @return static
     */
    public static function create($_arg = null)
    {
        static::$instance = null;
        return static::instance(...$_arg);
    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed
     * @throws \Exception
     */
    public static function __callStatic($name, $arguments)
    {
        $instance = static::instance();
        $method = substr($name,1);
        if( strpos($name,"_") && is_callable([$instance,$method]))
            throw new \Exception("method not exists" . $name);
        return $instance->$method(...$arguments);
    }
}