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
namespace JYPHP\Core\Http;


use JYPHP\Core\Exception\JyException;

class Response
{
    /**
     * HTTP 状态码
     * @var int
     */
    protected $status = 200;

    /**
     * 源数据
     * @var mixed
     */
    protected $data;

    /**
     * @var \Swoole\Http\Response
     */
    protected $response;

    /**
     * 返回内容的格式
     * @var string
     */
    protected $contentType = "text/html";

    /**
     * 编码
     * @var string
     */
    protected $charset = "ust-8";

    /**
     * 使用的http协议版本
     * @var string
     */
    protected $http_protocol = "HTTP/1.1";

    /**
     * 输出数据
     * @var string
     */
    protected $body;

    /**
     * http status
     * @var array
     */
    protected static $HTTP_HEADERS = [
        100 => "100 Continue",
        101 => "101 Switching Protocols",
        200 => "200 OK",
        201 => "201 Created",
        204 => "204 No Content",
        206 => "206 Partial Content",
        300 => "300 Multiple Choices",
        301 => "301 Moved Permanently",
        302 => "302 Found",
        303 => "303 See Other",
        304 => "304 Not Modified",
        307 => "307 Temporary Redirect",
        400 => "400 Bad Request",
        401 => "401 Unauthorized",
        403 => "403 Forbidden",
        404 => "404 Not Found",
        405 => "405 Method Not Allowed",
        406 => "406 Not Acceptable",
        408 => "408 Request Timeout",
        410 => "410 Gone",
        413 => "413 Request Entity Too Large",
        414 => "414 Request URI Too Long",
        415 => "415 Unsupported Media Type",
        416 => "416 Requested Range Not Satisfiable",
        417 => "417 Expectation Failed",
        500 => "500 Internal Server Error",
        501 => "501 Method Not Implemented",
        503 => "503 Service Unavailable",
        506 => "506 Variant Also Negotiates",
    ];

    /**
     * 处理输出数据
     */
    public function body() : void
    {
        if(is_array($this->data)){
            $this->body = json_encode($this->data);
        }
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return mixed
     * @throws JyException
     */
    public function __call(string $name, array $arguments)
    {
        if (method_exists($this->response, $name)) {
            return $this->response->$name(...$arguments);
        }
        throw new JyException($name . "方法不存在", "500");
    }

    /**
     * Response constructor.
     * @param string $content
     * @param int $status
     * @param array $headers
     */
    public function __construct(string $content = "", int $status = 200 , array $headers = [])
    {
        $this->response = app()->make("response");
        $this->data = $content;
        $this->status = $status;
        foreach($headers as $key => $item){
            $this->header($key,$item);
        }
    }

    /**
     * 通知浏览器不缓存
     */
    public function noCache(): void
    {
        $this->header('Cache-Control', 'no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->header('Pragma', 'no-cache');
    }

    /**
     * @return mixed
     */
    public function send()
    {
        $this->header('Status',self::$HTTP_HEADERS[$this->status]);
        $this->header("Content-Type",$this->contentType.";charset=".$this->charset);
        $this->response->status($this->status);
        return $this->response->end($this->body);
    }
}