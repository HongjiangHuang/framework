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
namespace JYPHP\Core\Config;
use JYPHP\Core\Exception\JyException;

/**
 * 配置管理类
 * @package JYPHP\Core\Config
 */
class ConfigManage
{
    protected $namespace = "app";

    public function __construct($namespace = "app")
    {
        $this->namespace = $namespace;
    }

    public function get($key,$default = null)
    {
        Config::setNamespace($this->namespace);
        $config =  Config::get($key,$default);
        if ($config === null)
            throw new JyException("配置".$this->namespace."下的".$key."未定义");
        return $config;
    }

    public function has($key)
    {
        Config::setNamespace($this->namespace);
        return Config::has($key);
    }

    public function getAll()
    {
        return Config::getNamespace($this->namespace);
    }
}