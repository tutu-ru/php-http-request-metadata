<?php
declare(strict_types=1);

namespace TutuRu\HttpRequestMetadata;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Server\MiddlewareInterface;
use TutuRu\RequestMetadata\RequestMetadata;

class RequestMetadataMiddleware implements MiddlewareInterface
{
    /** @var RequestMetadataHandler */
    private $requestMetadataHandler;

    /** @var ResponseMetadataHandler */
    private $responseMetadataHandler;


    public function __construct(RequestMetadata $requestMetadata)
    {
        $this->requestMetadataHandler = new RequestMetadataHandler($requestMetadata);
        $this->responseMetadataHandler = new ResponseMetadataHandler($requestMetadata);
    }


    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($this->requestMetadataHandler->isRequestContainRequestId($request)) {
            $this->requestMetadataHandler->initFromRequest($request);
        } else {
            $this->requestMetadataHandler->initFromScratch();
        }

        $response = $handler->handle($request);

        return $this->responseMetadataHandler->addToResponse($response);
    }
}
