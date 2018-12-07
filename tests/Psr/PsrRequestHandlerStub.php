<?php
declare(strict_types=1);

namespace TutuRu\Tests\HttpRequestMetadata\Psr;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class PsrRequestHandlerStub implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return new PsrResponseStub();
    }
}
