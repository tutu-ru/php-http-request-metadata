<?php
declare(strict_types=1);

namespace TutuRu\Tests\HttpRequestMetadata;

use TutuRu\HttpRequestMetadata\RequestMetadataHandler;
use TutuRu\HttpRequestMetadata\RequestMetadataMiddleware;
use TutuRu\HttpRequestMetadata\ResponseMetadataHandler;
use TutuRu\RequestMetadata\RequestMetadata;
use TutuRu\Tests\HttpRequestMetadata\Psr\PsrRequestHandlerStub;
use TutuRu\Tests\HttpRequestMetadata\Psr\PsrServerRequestStub;

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
     *
     * @param array $headers
     * @param array $expectedAttributes
     */
    public function testProcessWithRequestId($headers, $expectedAttributes)
    {
        $requestMetadata = new RequestMetadata();
        $this->assertNull($requestMetadata->get(RequestMetadata::ATTR_REQUEST_ID));

        $middleware = new RequestMetadataMiddleware(new RequestMetadataHandler($requestMetadata));
        $middleware->process(new PsrServerRequestStub($headers), new PsrRequestHandlerStub());

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
     *
     * @param array $headers
     */
    public function testProcessWithoutRequestId($headers)
    {
        $requestMetadata = new RequestMetadata();
        $this->assertNull($requestMetadata->get(RequestMetadata::ATTR_REQUEST_ID));

        $middleware = new RequestMetadataMiddleware(new RequestMetadataHandler($requestMetadata));
        $middleware->process(new PsrServerRequestStub($headers), new PsrRequestHandlerStub());

        $this->assertCount(1, $requestMetadata->getAttributes());
        $this->assertNotNull($requestMetadata->get(RequestMetadata::ATTR_REQUEST_ID));
    }


    public function testResponseWithoutResponseHandler()
    {
        $requestMetadata = new RequestMetadata();
        $headers = ['tutu-request-id' => 'abc-def'];
        $middleware = new RequestMetadataMiddleware(new RequestMetadataHandler($requestMetadata));
        $response = $middleware->process(new PsrServerRequestStub($headers), new PsrRequestHandlerStub());

        $this->assertEquals([], $response->getHeader('tutu-request-id'));
    }


    public function testResponseWithResponseHandler()
    {
        $requestMetadata = new RequestMetadata();
        $headers = ['tutu-request-id' => 'abc-def'];
        $middleware = new RequestMetadataMiddleware(
            new RequestMetadataHandler($requestMetadata),
            new ResponseMetadataHandler($requestMetadata)
        );
        $response = $middleware->process(new PsrServerRequestStub($headers), new PsrRequestHandlerStub());

        $this->assertEquals(
            [$requestMetadata->get(RequestMetadata::ATTR_REQUEST_ID)],
            $response->getHeader('tutu-request-id')
        );
    }
}
