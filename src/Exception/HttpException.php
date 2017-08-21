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
namespace JYPHP\Core\Exception;

use Exception;

class HttpException extends JyException
{
    public function __construct($message = "", $code = 400, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}