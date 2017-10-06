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

use JYPHP\Core\Exception\HttpException;
use JYPHP\Core\Exception\NotFoundException;
use JYPHP\Core\Interfaces\Http\IResponse;

abstract class Controller
{
    /**
     * 未命中method
     * @throws NotFoundException
     */
    public function missMethod()
    {
        throw new NotFoundException("页面找不到");
    }

    /**
     * @param $data
     * @return mixed
     */
    public function toResponse($data)
    {
        return $data;
    }

    /**
     * 处理错误
     * @param $exception
     * @return IResponse
     */
    public function dealWithError(HttpException $exception): IResponse
    {
        return app(IResponse::class,
            [
                "content" => $this->toResponse(
                    [
                        "errMsg" => $exception->getMessage(),
                        "errCode" => $exception->getCode(),
                        "data" => ""
                    ]
                ),
                "status" => $exception->getCode()
            ]
        );
    }

    public function getContentType(): string
    {
        return "text/html";
    }
}