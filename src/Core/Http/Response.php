<?php

namespace App\Core\Http;

class Response
{
    protected ?string $content;
    protected int $statusCode;
    private array $headers;

    public static array $statusTexts = [
        200 => 'OK',
        201 => 'Created',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        500 => 'Internal Server Error',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
    ];

    public function __construct(?string $content = '', int $status = 200, array $headers = [])
    {
        $this->content = $content;
        $this->statusCode = $status;
        $this->headers = $headers;
    }

    public function __toString(): string
    {
        return
            sprintf('HTTP/1.1 %s %s', $this->statusCode, self::$statusTexts[$this->statusCode]) . "\r\n" .
            $this->getHeaders() . "\r\n" .
            $this->getContent();
    }

    private function getContent(): ?string
    {
        return $this->content;
    }

    private function getHeaders(): string
    {
        $headers = array_merge([
            'Cache-Control' => 'no-cache, private',
            'Date' => gmdate('D, d M Y H:i:s') . ' GMT',
        ], $this->headers);

        return implode('\r\n', array_map(static function ($k, $v) {
            return "$k:$v";
        }, array_keys($headers), $headers));
    }
}