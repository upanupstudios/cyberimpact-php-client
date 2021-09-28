<?php

namespace Upanupstudios\Cyberimpact\Php\Client;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamInterface;

abstract class AbstractApi
{
  /**
   * @var Cyberimpact
   */
  protected $client;

  public function __construct(Cyberimpact $client)
  {
      $this->client = $client;
  }

  protected function createRequest(string $method, string $uri): RequestInterface
  {
    $request = $this->client->getRequestFactory()->createRequest($method, $this->client->getApiUrl().$uri);
    $request = $request->withHeader('Accept', 'application/json');

    return $request;
  }

  /**
   * @param mixed $value
   */
  protected function createStream($value, int $flags = null, int $maxDepth = 512): StreamInterface
  {
      $flags = $flags ?? (JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT | JSON_PRESERVE_ZERO_FRACTION);

      try {
          $value = json_encode($value, $flags | JSON_THROW_ON_ERROR, $maxDepth);
      } catch (JsonException $e) {
          throw new InvalidArgumentException("Invalid value for json encoding: {$e->getMessage()}.");
      }

      return $this->client->getStreamFactory()->createStream($value);
  }
}