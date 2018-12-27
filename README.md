# Библиотека HttpRequestMetadata

PSR-15 middleware для передачи стандартных заголовков запросов в хранилище метаданных (RequestMetadata).

Создание:
```php
use TutuRu\HttpRequestMetadata\RequestMetadataMiddleware;

// $requestMetadata должен быть создан ранее

$middleware = new RequestMetadataMiddleware($requestMetadata);
```

Добавление заголовков в PSR-7 запросы (на примере Guzzle):
```php
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use Psr\Http\Message\RequestInterface;
use TutuRu\HttpRequestMetadata\RequestMetadataHandler;

// $requestMetadata должен быть создан ранее

$handlerStack = HandlerStack::create();
$handlerStack->push(Middleware::mapRequest(
    function (RequestInterface $request) use ($requestMetadata) {
        return (new RequestMetadataHandler($requestMetadata))->addToRequest($request);
    }
));
$client = new Client(['handler' => $handlerStack]);
```
