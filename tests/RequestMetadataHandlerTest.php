<?php
declare(strict_types=1);

namespace TutuRu\Tests\HttpRequestMetadata;

use TutuRu\HttpRequestMetadata\RequestMetadataHandler;
use TutuRu\RequestMetadata\RequestMetadata;

class RequestMetadataHandlerTest extends BaseTest
{
    public function isHttpRequestContainRequestIdDataProvider()
    {
        return [
            [['tutu-request-id' => 'abc'], true],
            [['x-request-id' => 'abc'], true],
            [['request-id' => 'abc'], false],
            [[], false],
        ];
    }


    /**
     * @dataProvider isHttpRequestContainRequestIdDataProvider
     */
    public function testIsHttpRequestContainRequestId($headers, $expectedResult)
    {
        $requestMetadata = new RequestMetadata();
        $httpHandler = new RequestMetadataHandler($requestMetadata);
        $this->assertEquals(
            $expectedResult,
            $httpHandler->isRequestContainRequestId($this->createRequest($headers))
        );
    }


    public function initFromHttpRequestDataProvider()
    {
        return [
            // empty
            [[], []],
            // check all headers on by one
            [['tutu-request-id' => 'abc-def'], ['RequestId' => 'abc-def']],
            [['x-request-id' => 'abc-def'], ['RequestId' => 'abc-def']],
            [['tutu-request-id' => 'abc', 'x-request-id' => 'def'], ['RequestId' => 'abc']],
            [['tutu-sid' => 'def'], ['sid' => 'def']],
            [['tutu-uid' => 'abc'], ['uid' => 'abc']],
            [['tutu-jwt' => 'qwerty'], ['jwt' => 'qwerty']],
            [['tutu-localization' => 'ru'], ['localization' => 'ru']],
            [['tutu-currency' => 'rub'], ['currency' => 'rub']],
            // check forbidden headers
            [['custom-header' => '1'], []],
            [['tutu-header' => '1'], []],
            [['tutu-localization' => 'ru', 'tutu-header' => 'test'], ['localization' => 'ru']],
        ];
    }


    /**
     * @dataProvider initFromHttpRequestDataProvider
     */
    public function testInitFromHttpRequest($headers, $expectedAttributes)
    {
        $requestMetadata = new RequestMetadata();
        $httpHandler = new RequestMetadataHandler($requestMetadata);
        $httpHandler->initFromRequest($this->createRequest($headers));

        $this->assertEquals($expectedAttributes, $requestMetadata->getAttributes());
    }


    public function addToHttpRequestDataProvider()
    {
        return [
            // empty
            [[], []],
            // check all attributes on by one
            [['RequestId' => 'abc-def'], ['tutu-request-id' => ['abc-def'], 'x-request-id' => ['abc-def']]],
            [['sid' => 'def'], ['tutu-sid' => ['def']]],
            [['uid' => 'abc'], ['tutu-uid' => ['abc']]],
            [['jwt' => 'qwerty'], ['tutu-jwt' => ['qwerty']]],
            [['localization' => 'ru'], ['tutu-localization' => ['ru']]],
            [['currency' => 'rub'], ['tutu-currency' => ['rub']]],
            // check forbidden attributes
            [['test' => 'test'], []],
            [['sid' => 'def', 'test' => 'test'], ['tutu-sid' => ['def']]],
        ];
    }


    /**
     * @dataProvider addToHttpRequestDataProvider
     */
    public function testAddToHttpRequest($attributes, $expectedHeaders)
    {
        $requestMetadata = new RequestMetadata();
        foreach ($attributes as $attributeName => $attributeValue) {
            $requestMetadata->set($attributeName, $attributeValue);
        }
        $httpHandler = new RequestMetadataHandler($requestMetadata);
        $request = $httpHandler->addToRequest($this->createRequest());

        $this->assertEquals($expectedHeaders, $request->getHeaders());
    }
}
