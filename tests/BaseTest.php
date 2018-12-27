<?php
declare(strict_types=1);

namespace TutuRu\Tests\HttpRequestMetadata;

use Middlewares\Utils\Factory;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;

abstract class BaseTest extends TestCase
{
    protected function createRequest(array $headers = []): ServerRequestInterface
    {
        $request = Factory::createServerRequest('GET', '/');
        foreach ($headers as $name => $value) {
            $request = $request->withHeader($name, $value);
        }
        return $request;
    }
}
