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
use \JYPHP\Core\Config\ConfigManage;

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
 * 获得ConfigManage
 */
if (!function_exists('config')) {
    function config($namespace = 'app') : ConfigManage
    {
        return app()->makeWith('config',['namespace' => $namespace]);
    }
}