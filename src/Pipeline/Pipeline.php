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
declare(strict_types=1);
namespace JYPHP\Core\Pipeline;

use JYPHP\Core\Interfaces\Application\IApplication;

class Pipeline extends \Illuminate\Pipeline\Pipeline
{
    public function __construct(IApplication $application = null)
    {
        parent::__construct($application);
    }
}