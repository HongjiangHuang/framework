<?php
// +----------------------------------------------------------------------
// | JYPHP [ JUST YOU ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017~2017 http://www.jyphp.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: Albert <albert_p@foxmail.com>
// +----------------------------------------------------------------------
declare(strict_types = 1);
/**
 * 获得全局app对象
 */
use Symfony\Component\HttpFoundation\Request;

if (!function_exists("app")) {

    /**
     * 获取Application实例或make
     * @param null|string $abstract
     * @param array $parameters
     * @return \JYPHP\Core\Application|mixed
     */
    function app(?string $abstract = null, array $parameters = [])
    {
        if (is_null($abstract))
            return \JYPHP\Core\Application::getInstance();
        return empty($parameters)
            ? \JYPHP\Core\Application::getInstance()->make($abstract)
            : \JYPHP\Core\Application::getInstance()->makeWith($abstract, $parameters);
    }

}

/**
 * 获得全局response对象
 */
if (!function_exists("response")) {
    function response(): \Swoole\Http\Response
    {
        return app()->make('response');
    }
}

/**
 * 获得全局request对象
 */
if (!function_exists('request')) {
    function request(): Request
    {
        return app()->make('request');
    }
}

/**
 * Config manage class
 */
if (!function_exists('config')) {
    function config($key = null, $default = null)
    {
        return $key === null ? app('config')
            : ($default === null ? app('config')[$key]
                : app('config')->get($key, $default));
    }
}


if (!function_exists('session')) {
    /**
     * Get / set the specified session value.
     *
     * If an array is passed as the key, we will assume you want to set an array of values.
     *
     * @param  array|string $key
     * @param  mixed $default
     * @return mixed
     */
    function session($key = null, $default = null)
    {
        if (is_null($key)) {
            return app('session');
        }

        if (is_array($key)) {
            return app('session')->put($key);
        }

        return app('session')->get($key, $default);
    }
}

if (!function_exists('dir_each')) {

    /**
     * 遍历目录
     * @param string $dir 目录路径
     * @param callable $func
     * @throws Exception
     */
    function dir_each(string $dir, callable $func)
    {
        if (!is_dir($dir)) {
            throw new \Exception("${dir} not is directory");
        }
        $res = opendir($dir);
        while (($file = readdir($res)) !== false) {
            call_user_func($func, $file);
        }
    }

}