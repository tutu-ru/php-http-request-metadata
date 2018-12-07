<?php
declare(strict_types=1);

namespace TutuRu\Tests\HttpRequestMetadata\Psr;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class PsrResponseStub extends PsrMessageStub implements ResponseInterface
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


    public function getStatusCode()
    {
        throw new \RuntimeException("Not implemented");
    }


    public function withStatus($code, $reasonPhrase = '')
    {
        throw new \RuntimeException("Not implemented");
    }


    public function getReasonPhrase()
    {
        throw new \RuntimeException("Not implemented");
    }
}
