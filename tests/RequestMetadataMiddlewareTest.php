<?php
declare(strict_types=1);

namespace TutuRu\Tests\HttpRequestMetadata;

use Middlewares\Utils\Dispatcher;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TutuRu\HttpRequestMetadata\RequestMetadataMiddleware;
use TutuRu\RequestMetadata\RequestMetadata;

class RequestMetadataMiddlewareTest extends BaseTest
{
    public function headersWithRequestIdDataProvider()
    {
        return [
            [['tutu-request-id' => 'abc-def'], ['RequestId' => 'abc-def']],
            [['tutu-request-id' => 'abc-def', 'tutu-sid' => 'def'], ['RequestId' => 'abc-def', 'sid' => 'def']],
            [['tutu-request-id' => 'abc-def', 'tutu-header' => '1'], ['RequestId' => 'abc-def']],
            [['tutu-request-id' => 'abc-def', 'custom-header' => '1'], ['RequestId' => 'abc-def']],
        ];
    }


    /**
     * @dataProvider headersWithRequestIdDataProvider
     */
    public function testProcessWithRequestId($headers, $expectedAttributes)
    {
        $requestMetadata = new RequestMetadata();
        $this->assertNull($requestMetadata->get(RequestMetadata::ATTR_REQUEST_ID));

        $this->processRequest($this->createRequest($headers), $requestMetadata);
        $this->assertEquals($expectedAttributes, $requestMetadata->getAttributes());
    }


    public function headersWithoutRequestIdDataProvider()
    {
        return [
            [[]],
            [['tutu-sid' => 'def']],
            [['tutu-header' => '1']],
            [['custom-header' => '1']],
        ];
    }


    /**
     * @dataProvider headersWithoutRequestIdDataProvider
     */
    public function testProcessWithoutRequestId($headers)
    {
        $requestMetadata = new RequestMetadata();
        $this->assertNull($requestMetadata->get(RequestMetadata::ATTR_REQUEST_ID));

        $this->processRequest($this->createRequest($headers), $requestMetadata);
        $this->assertCount(1, $requestMetadata->getAttributes());
        $this->assertNotNull($requestMetadata->get(RequestMetadata::ATTR_REQUEST_ID));
    }


    /**
     * @dataProvider responseDataProvider
     */
    public function testResponse($headers)
    {
        $requestMetadata = new RequestMetadata();
        $response = $this->processRequest($this->createRequest($headers), $requestMetadata);
        $this->assertNotEmpty($response->getHeader('tutu-request-id'));
        $this->assertEquals(
            [$requestMetadata->get(RequestMetadata::ATTR_REQUEST_ID)],
            $response->getHeader('tutu-request-id')
        );
    }


    public function responseDataProvider()
    {
        return [
            [[]],
            [['tutu-request-id' => 'abc-def']],
            [['x-request-id' => 'abc-def']]
        ];
    }


    private function processRequest(
        ServerRequestInterface $request,
        RequestMetadata $requestMetadata
    ): ResponseInterface {
        return Dispatcher::run(
            [
                new RequestMetadataMiddleware($requestMetadata),
                function ($request, $next) {
                    /** @var ResponseInterface $response */
                    /** @var RequestHandlerInterface $next */
                    $response = $next->handle($request);
                    return $response->withStatus('200', 'ok');
                }
            ],
            $request
        );
    }
}
