<?php

namespace Upanupstudios\Cyberimpact\Php\Client;

use Symfony\Component\HttpClient\Psr18Client;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\RequestInterface;

class Cyberimpact
{
  /**
   * The REST API URL.
   *
   * @var string $api_url
   */
  private $api_url = 'https://api.cyberimpact.com';

  private $config;
  private $httpClient;
  private $requestFactory;
  private $streamFactory;

  /**
   * @param string $method Authentication method (self::METHOD_BASIC or self::METHOD_JWT)
   * @param string $user Username or Token
   * @param string $password Password for Basic Auth
   * @throws Exception if $method is not basic or jwt
   */
  public function __construct(Config $config, ClientInterface $httpClient, RequestFactoryInterface $requestFactory, StreamFactoryInterface $streamFactory)
  {
    $this->config = $config;
    $this->httpClient = $httpClient;
    $this->requestFactory = $requestFactory;
    $this->streamFactory = $streamFactory;
  }

  public function getConfig(): Config
  {
      return $this->config;
  }

  public function getRequestFactory(): RequestFactoryInterface
  {
      return $this->requestFactory;
  }

  public function getStreamFactory(): StreamFactoryInterface
  {
      return $this->streamFactory;
  }

  public function sendRequest(RequestInterface $request): array
  {
      $response = $this->httpClient->sendRequest($request);

      $contentType = $response->getHeader('Content-Type')[0] ?? 'application/json';

      if (!preg_match('/\bjson\b/i', $contentType)) {
          throw new JsonException("Response content-type is '$contentType' while a JSON-compatible one was expected.");
      }

      $content = $response->getBody()->__toString();

      try {
          $content = json_decode($content, true, 512, JSON_BIGINT_AS_STRING | JSON_THROW_ON_ERROR);
      } catch (\JsonException $e) {
          throw new JsonException($e->getMessage(), $e->getCode(), $e);
      }

      if (!is_array($content)) {
          throw new JsonException(sprintf('JSON content was expected to decode to an array, %s returned.', gettype($content)));
      }

      return $content;
  }

  public function ping() {
    $request = $this->getRequestFactory()->createRequest('GET', $this->api_url.'/ping');
    $request = $request->withHeader('Authorization', "Bearer {$this->getConfig()->getApiToken()}");

    return $this->sendRequest($request);
  }
}