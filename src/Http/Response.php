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
namespace JYPHP\Core\Http;

use JYPHP\Core\Exception\JyException;
use JYPHP\Core\Interfaces\Http\IResponse;

class Response implements IResponse
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
    protected $charset = "utf-8";

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


    //200 OK - [GET]：服务器成功返回用户请求的数据，该操作是幂等的（Idempotent）。
    //201 CREATED - [POST/PUT/PATCH]：用户新建或修改数据成功。
    //202 Accepted - [*]：表示一个请求已经进入后台排队（异步任务）
    //204 NO CONTENT - [DELETE]：用户删除数据成功。
    //400 INVALID REQUEST - [POST/PUT/PATCH]：用户发出的请求有错误，服务器没有进行新建或修改数据的操作，该操作是幂等的。
    //401 Unauthorized - [*]：表示用户没有权限（令牌、用户名、密码错误）。
    //403 Forbidden - [*] 表示用户得到授权（与401错误相对），但是访问是被禁止的。
    //404 NOT FOUND - [*]：用户发出的请求针对的是不存在的记录，服务器没有进行操作，该操作是幂等的。
    //406 Not Acceptable - [GET]：用户请求的格式不可得（比如用户请求JSON格式，但是只有XML格式）。
    //410 Gone -[GET]：用户请求的资源被永久删除，且不会再得到的。
    //422 Unprocesable entity - [POST/PUT/PATCH] 当创建一个对象时，发生一个验证错误。
    //500 INTERNAL SERVER ERROR - [*]：服务器发生错误，用户将无法判断发出的请求是否成功。

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
    public function body(): self
    {
        if (is_array($this->data)) {
            $this->body = json_encode($this->data);
        } else if (is_object($this->data)) {
            if (is_callable([$this->data, "__toString"])) {
                $this->body = (string)$this->data;
            } else {
                throw new \InvalidArgumentException(sprintf('variable type error： %s', gettype($this->data)));
            }
        } else if (is_string($this->data)) {
            $this->body = $this->data;
        } else {
            $this->body = (string)$this->data;
        }
        return $this;
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
     * 设置内容类型
     * text/html
     * text/json
     * text/xml
     * ...
     * @param $content_type
     * @return mixed
     */
    public function setContentType($content_type)
    {
        return $this->contentType = $content_type;
    }

    /**
     * Response constructor.
     * @param string|array|\ArrayAccess $content
     * @param int $status
     * @param array $headers
     */
    public function __construct($content = "", int $status = 200 , array $headers = [])
    {
        $this->response = app()->make("response");
        $this->data = $content;
        $this->body();
        $this->status = $status;
        foreach ($headers as $key => $item) {
            $this->header($key, $item);
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
        $this->header('Status', self::$HTTP_HEADERS[$this->status]);
        $this->header("Content-Type", $this->contentType . ";charset=" . $this->charset);
        $this->response->status($this->status);
        return $this->response->end($this->body);
    }

    /**
     * 设置请求头
     * @param string $header_string
     * @param string $header_value
     * @return mixed
     */
    public function header(string $header_string, string $header_value)
    {
        return $this->response->header($header_string, $header_value);
    }

    /**
     * 设置Cookie
     * @param string $key
     * @param string $value
     * @param int $expire
     * @param string $path
     * @param string $domain
     * @param bool $secure
     * @param bool $httponly
     * @return mixed
     */
    public function cookie(string $key, string $value = "", int $expire = 0, string $path = '/', string $domain = '', bool $secure = false, bool $httponly = false)
    {
        return $this->response->cookie($key, $value, $expire, $path, $domain, $secure, $httponly);
    }

    /**
     * 改变响应状态码
     * @param int $http_status_code
     * @return mixed
     */
    public function status(int $http_status_code)
    {
        return $this->response->status($http_status_code);
    }

    /**
     * 压缩
     * 最大等级为9
     * @param int $level
     * @return mixed
     */
    public function gzip(int $level = 1)
    {
        return $this->response->gzip($level);
    }

    public function write(string $data): bool
    {
        return $this->response->write($data);
    }

    public function sendfile(string $filename, int $offset = 0, int $length = 0)
    {
        return $this->response->sendfile($filename, $offset, $length);
    }
}