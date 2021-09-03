<?php

namespace Upanupstudios\Cyberimpact\Php\Client;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;

class Cyberimpact
{
  /**
   * The REST API URL.
   *
   * @var string $api_url
   */
  private $api_url = 'https://api.cyberimpact.com';

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

  public function ping() {
    // Set default request options with auth header.
    $options = [
      'headers' => [
        'Content-Type' => 'application/json',
        'Authorization' => 'Bearer ' . $this->api_token,
      ],
    ];

    // Add trigger error header if a debug error code has been set.
    if (!empty($this->debug_error_code)) {
      $options['headers']['X-Trigger-Error'] = $this->debug_error_code;
    }

    return $this->client->request('GET', $this->api_url . '/ping', (array) $options);
  }
}