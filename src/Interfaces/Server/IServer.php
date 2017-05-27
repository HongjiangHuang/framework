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
declare(strict_types=1);
namespace JYPHP\Core\Interfaces\Server;

interface IServer
{
    public function setWorkNum(int $num);
    public function setRequestMax(int $max);
    public function onClose();
    public function version();
    public function onShutdown();
}