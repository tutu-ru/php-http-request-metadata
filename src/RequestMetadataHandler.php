<?php
declare(strict_types=1);

namespace TutuRu\HttpRequestMetadata;

use Psr\Http\Message\RequestInterface;
use TutuRu\RequestMetadata\RequestMetadata;

class RequestMetadataHandler
{
    public const HTTP_REQUEST_ID = 'tutu-request-id';
    public const HTTP_SESSION_ID = 'tutu-sid';
    public const HTTP_USER_ID = 'tutu-uid';
    public const HTTP_LOCALIZATION = 'tutu-localization';
    public const HTTP_CURRENCY = 'tutu-currency';
    public const HTTP_JWT = 'tutu-jwt';

    public const HTTP_BC_REQUEST_ID = 'x-request-id';


    /** @var RequestMetadata */
    private $requestMetadata;


    public function __construct(RequestMetadata $requestMetadata)
    {
        $this->requestMetadata = $requestMetadata;
    }


    public function isRequestContainRequestId(RequestInterface $request): bool
    {
        return $request->hasHeader(self::HTTP_REQUEST_ID) || $request->hasHeader(self::HTTP_BC_REQUEST_ID);
    }


    public function initFromScratch(): void
    {
        $this->requestMetadata->init();
    }


    public function initFromRequest(RequestInterface $request): void
    {
        $this->requestMetadata->clear();
        foreach (array_flip($this->getHeaderNameToAttributeNameMap()) as $attributeName => $httpHeaderName) {
            if ($request->hasHeader($httpHeaderName)) {
                $this->requestMetadata->set($attributeName, $request->getHeaderLine($httpHeaderName));
            }
        }

        // backward compatibility
        if ($this->isRequestWithOldRequestId($request)) {
            $this->requestMetadata->set(
                RequestMetadata::ATTR_REQUEST_ID,
                $request->getHeaderLine(self::HTTP_BC_REQUEST_ID)
            );
        }
    }


    public function addToRequest(RequestInterface $request): RequestInterface
    {
        $result = $request;
        foreach ($this->getHeaderNameToAttributeNameMap() as $httpHeaderName => $attributeName) {
            $attribute = $this->requestMetadata->get($attributeName);
            if ($attribute) {
                $result = $result->withHeader($httpHeaderName, $attribute);
            }
        }

        // backward compatibility
        $xRequestId = $this->requestMetadata->get(RequestMetadata::ATTR_REQUEST_ID);
        if ($xRequestId) {
            $result = $result->withHeader(self::HTTP_BC_REQUEST_ID, $xRequestId);
        }

        return $result;
    }


    private function isRequestWithOldRequestId(RequestInterface $request): bool
    {
        return $request->hasHeader(self::HTTP_BC_REQUEST_ID) && !$request->hasHeader(self::HTTP_REQUEST_ID);
    }


    /**
     * @return string[]
     */
    private function getHeaderNameToAttributeNameMap(): array
    {
        return [
            self::HTTP_REQUEST_ID   => RequestMetadata::ATTR_REQUEST_ID,
            self::HTTP_SESSION_ID   => RequestMetadata::ATTR_SESSION_ID,
            self::HTTP_USER_ID      => RequestMetadata::ATTR_USER_ID,
            self::HTTP_JWT          => RequestMetadata::ATTR_JWT,
            self::HTTP_LOCALIZATION => RequestMetadata::ATTR_LOCALIZATION,
            self::HTTP_CURRENCY     => RequestMetadata::ATTR_CURRENCY,
        ];
    }
}
