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
}