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

trait Xml
{
    public function toResponse($data)
    {
        if(is_object($data) && method_exists($data,"__toString")){
            return (string)$data;
        }
        if(!is_array($data)){
            $data = [ config('api.code_field','errCode') => 200 , config('api.msg_field','errMsg') => "ok" , config('api.data_field','data') => $data];
        }
        return $this->arrayToXml($data);
    }

    /**
     * 参数转换成XML
     * @param array $arr 参数数组
     * @return string
     */
    public function arrayToXml($arr)
    {
        $xml = "<xml>";
        foreach ($arr as $key => $val) {
            if (is_numeric($val)) {
                $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
            } else {
                $xml .= "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
            }
        }
        $xml .= "</xml>";
        return $xml;
    }

    public function getContentType() : string
    {
        return "text/xml";
    }
}