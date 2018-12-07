<?php
declare(strict_types=1);

namespace TutuRu\HttpRequestMetadata;

use Psr\Http\Message\ResponseInterface;
use TutuRu\RequestMetadata\RequestMetadata;

class ResponseMetadataHandler
{
    /** @var RequestMetadata */
    private $requestMetadata;


    public function __construct(RequestMetadata $requestMetadata)
    {
        $this->requestMetadata = $requestMetadata;
    }


    public function addToResponse(ResponseInterface $response): ResponseInterface
    {
        if ($response->hasHeader(RequestMetadataHandler::HTTP_REQUEST_ID)) {
            return $response;
        }

        $xRequestId = $this->requestMetadata->get(RequestMetadata::ATTR_REQUEST_ID);
        if (!$xRequestId) {
            return $response;
        }
        return $response->withHeader(RequestMetadataHandler::HTTP_REQUEST_ID, $xRequestId);
    }
}
