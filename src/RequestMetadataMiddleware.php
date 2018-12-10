<?php
declare(strict_types=1);

namespace TutuRu\HttpRequestMetadata;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RequestMetadataMiddleware
{
    /** @var RequestMetadataHandler */
    private $requestMetadataHandler;

    /** @var ResponseMetadataHandler */
    private $responseMetadataHandler;


    public function __construct(
        RequestMetadataHandler $requestMetadataHandler,
        ?ResponseMetadataHandler $responseMetadataHandler = null
    ) {
        $this->requestMetadataHandler = $requestMetadataHandler;
        $this->responseMetadataHandler = $responseMetadataHandler;
    }


    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($this->requestMetadataHandler->isRequestContainRequestId($request)) {
            $this->requestMetadataHandler->initFromRequest($request);
        } else {
            $this->requestMetadataHandler->initFromScratch();
        }

        $response = $handler->handle($request);

        if (!is_null($this->responseMetadataHandler)) {
            $response = $this->responseMetadataHandler->addToResponse($response);
        }

        return $response;
    }
}
