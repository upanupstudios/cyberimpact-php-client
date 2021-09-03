<?php

namespace UpanupStudios\Cyberimpact;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;

class Cyberimpact
{
  public const API_URL = 'https://api.cyberimpact.com';

  /**
   * The Cyberimpact API token to authenticate with.
   *
   * @var string $api_token
   */
  private $api_token;

  /**
   * @param string $method Authentication method (self::METHOD_BASIC or self::METHOD_JWT)
   * @param string $user Username or Token
   * @param string $password Password for Basic Auth
   * @throws Exception if $method is not basic or jwt
   */
  public function __construct($api_token, ClientInterface $client = NULL, $http_options = []) {
    $this->api_token = $api_token;

    if (!empty($client)) {
      $this->client = $client;
    }
    else {
      $this->client = $this->getDefaultHttpClient($http_options);
    }
  }

  /**
   * Instantiates a default HTTP client based on the local environment.
   *
   * @param array $http_options
   *   HTTP client options.
   *
   * @return CyberimpactHttpClientInterface
   *   The HTTP client.
   */
  private function getDefaultHttpClient($http_options) {
    // Process HTTP options.
    // Handle deprecated 'timeout' argument.
    if (is_int($http_options)) {
      $http_options = [
        'timeout' => $http_options,
      ];
    }

    // Default timeout is 10 seconds.
    $http_options += [
      'timeout' => 10,
    ];

    // Use Guzzle client.
    $client = new Client($http_options);

    return $client;
  }
}