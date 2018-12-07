<?php
declare(strict_types=1);

namespace TutuRu\Tests\HttpRequestMetadata\Psr;

abstract class PsrMessageStub
{
    private $headers = [];


    public function __construct(array $initialHeaders = [])
    {
        foreach ($initialHeaders as $title => $header) {
            $this->headers[strtolower($title)] = is_array($header) ? $header : [$header];
        }
    }


    public function getHeaders()
    {
        return $this->headers;
    }


    public function hasHeader($name)
    {
        return array_key_exists(strtolower($name), $this->headers);
    }


    public function getHeader($name)
    {
        return $this->headers[strtolower($name)] ?? [];
    }


    public function getHeaderLine($name)
    {
        return implode(',', $this->getHeader($name));
    }


    public function withHeader($name, $value)
    {
        $headers = $this->getHeaders();
        $headers[strtolower($name)] = is_array($value) ? $value : [$value];
        return new static($headers);
    }


    public function withAddedHeader($name, $value)
    {
        $name = strtolower($name);
        $headers = $this->getHeaders();
        if (!isset($headers[$name])) {
            $headers[$name] = [];
        }
        $headers[$name] = array_merge($headers[$name], is_array($value) ? $value : [$value]);
        return new static($headers);
    }


    public function withoutHeader($name)
    {
        $headers = $this->getHeaders();
        unset($headers[strtolower($name)]);
        return new static($headers);
    }
}
