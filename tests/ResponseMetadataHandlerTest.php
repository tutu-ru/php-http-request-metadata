<?php
declare(strict_types=1);

namespace TutuRu\Tests\HttpRequestMetadata;

use Middlewares\Utils\Factory;
use TutuRu\HttpRequestMetadata\RequestMetadataHandler;
use TutuRu\HttpRequestMetadata\ResponseMetadataHandler;
use TutuRu\RequestMetadata\RequestMetadata;

class ResponseMetadataHandlerTest extends BaseTest
{
    public function addToHttpResponseDataProvider()
    {
        return [
            // empty
            [[], []],
            [['RequestId' => 'abc-def'], ['tutu-request-id' => ['abc-def']]],
            [['sid' => 'def'], []],
            [['test' => 'test'], []],
            [['sid' => 'def', 'test' => 'test'], []],
        ];
    }


    /**
     * @dataProvider addToHttpResponseDataProvider
     *
     * @param array $attributes
     * @param array $expectedHeaders
     */
    public function testAddToHttpResponse($attributes, $expectedHeaders)
    {
        $requestMetadata = new RequestMetadata();
        foreach ($attributes as $attributeName => $attributeValue) {
            $requestMetadata->set($attributeName, $attributeValue);
        }
        $httpHandler = new ResponseMetadataHandler($requestMetadata);
        $response = $httpHandler->addToResponse(Factory::createResponse());

        $this->assertEquals($expectedHeaders, $response->getHeaders());
    }


    public function testAddToHttpResponseWithExistingId()
    {
        $requestMetadata = new RequestMetadata();
        $requestMetadata->set(RequestMetadata::ATTR_REQUEST_ID, 'new value');
        $httpHandler = new ResponseMetadataHandler($requestMetadata);

        $response = Factory::createResponse();
        $response = $response->withHeader(RequestMetadataHandler::HTTP_REQUEST_ID, 'old value');
        $response = $httpHandler->addToResponse($response);

        $this->assertEquals(['tutu-request-id' => ['old value']], $response->getHeaders());
    }
}
