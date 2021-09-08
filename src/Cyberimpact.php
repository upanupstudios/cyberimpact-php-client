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

  public function ping() {
    $request = $this->getRequestFactory()->createRequest('GET', $this->api_url.'/ping');
    $request = $request->withHeader('Authorization', "Bearer {$this->getConfig()->getApiToken()}");

    return $this->sendRequest($request);
  }
}