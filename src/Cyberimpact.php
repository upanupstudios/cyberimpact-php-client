<?php

namespace Upanupstudios\Cyberimpact\Php\Client;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;

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

  public function __construct(Config $config, ClientInterface $httpClient)
  {
    $this->config = $config;
    $this->httpClient = $httpClient;
  }

  public function getApiUrl()
  {
    return $this->api_url;
  }

  public function getConfig(): Config
  {
    return $this->config;
  }

  public function request(string $method, string $uri, array $options = [])
  {
    try {
      $defaultOptions = [
        'headers' => [
          'Accept' => 'application/json',
          'Content-Type' => 'application/json',
          'Authorization' => 'Bearer '.$this->config->getApiToken()
        ]
      ];

      if(!empty($options)) {
        //TODO: This might not be a deep merge...
        $options = array_merge($defaultOptions, $options);
      } else {
        $options = $defaultOptions;
      }

      $request = $this->httpClient->request($method, $this->api_url.'/'.$uri, $options);

      $body = $request->getBody();
      $response = $body->__toString();

      // Return as array
      $response = json_decode($response, TRUE);
    } catch (\JsonException $exeption) {
      $response = $exeption->getMessage();
    } catch (RequestException $exception) {
      $response = $exception->getMessage();
    }

    return $response;
  }

  public function ping()
  {
    $response = $this->request('GET', 'ping');

    return $response;
  }

  /**
   * @return object
   *
   * @throws \InvalidArgumentException
   *  If $class does not exist.
   */
  public function api(string $class)
  {
    switch ($class) {
      case 'groups':
        $api = new Groups($this);
        break;

      case 'mailings':
        $api = new Mailings($this);
        break;

      default:
        throw new \InvalidArgumentException("Undefined api instance called: '$class'.");
    }

    return $api;
  }

  public function __call(string $name, array $args): object
  {
    try {
        return $this->api($name);
    } catch (\InvalidArgumentException $e) {
        throw new \BadMethodCallException("Undefined method called: '$name'.");
    }
  }
}