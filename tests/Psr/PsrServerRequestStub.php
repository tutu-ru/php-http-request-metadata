<?php
declare(strict_types=1);

namespace TutuRu\Tests\HttpRequestMetadata\Psr;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

class PsrServerRequestStub extends PsrMessageStub implements ServerRequestInterface
{
    public function __construct(array $initialHeaders = [])
    {
        parent::__construct($initialHeaders);
    }


    public function getProtocolVersion()
    {
        throw new \RuntimeException("Not implemented");
    }


    public function withProtocolVersion($version)
    {
        throw new \RuntimeException("Not implemented");
    }


    public function getBody()
    {
        throw new \RuntimeException("Not implemented");
    }


    public function withBody(StreamInterface $body)
    {
        throw new \RuntimeException("Not implemented");
    }


    public function getRequestTarget()
    {
        throw new \RuntimeException("Not implemented");
    }


    public function withRequestTarget($requestTarget)
    {
        throw new \RuntimeException("Not implemented");
    }


    public function getMethod()
    {
        throw new \RuntimeException("Not implemented");
    }


    public function withMethod($method)
    {
        throw new \RuntimeException("Not implemented");
    }


    public function getUri()
    {
        throw new \RuntimeException("Not implemented");
    }


    public function withUri(UriInterface $uri, $preserveHost = false)
    {
        throw new \RuntimeException("Not implemented");
    }


    public function getServerParams()
    {
        throw new \RuntimeException("Not implemented");
    }


    public function getCookieParams()
    {
        throw new \RuntimeException("Not implemented");
    }


    public function withCookieParams(array $cookies)
    {
        throw new \RuntimeException("Not implemented");
    }


    public function getQueryParams()
    {
        throw new \RuntimeException("Not implemented");
    }


    public function withQueryParams(array $query)
    {
        throw new \RuntimeException("Not implemented");
    }


    public function getUploadedFiles()
    {
        throw new \RuntimeException("Not implemented");
    }


    public function withUploadedFiles(array $uploadedFiles)
    {
        throw new \RuntimeException("Not implemented");
    }


    public function getParsedBody()
    {
        throw new \RuntimeException("Not implemented");
    }


    public function withParsedBody($data)
    {
        throw new \RuntimeException("Not implemented");
    }


    public function getAttributes()
    {
        throw new \RuntimeException("Not implemented");
    }


    public function getAttribute($name, $default = null)
    {
        throw new \RuntimeException("Not implemented");
    }


    public function withAttribute($name, $value)
    {
        throw new \RuntimeException("Not implemented");
    }


    public function withoutAttribute($name)
    {
        throw new \RuntimeException("Not implemented");
    }
}
