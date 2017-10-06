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
namespace JYPHP\Core\Traits\Controller;

trait Json
{
    public function toResponse($data)
    {
        $data = $data ?: [];
        if (is_array($data)) {
            return json_encode($data);
        }
        if ((is_object($data) && method_exists($data, "__toString")) || is_string($data)) {
            return json_encode([config('api.code_field', 'errCode') => 200, config('api.msg_field', 'errMsg') => "ok", config('api.data_field', 'data') => $data]);
        }
        return $data;
    }

    public function getContentType(): string
    {
        return "text/json";
    }
}