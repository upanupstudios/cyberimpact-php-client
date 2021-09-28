<?php

namespace Upanupstudios\Cyberimpact\Php\Client;

class Mailings extends AbstractApi 
{
  public function __construct(Cyberimpact $client)
  {
    parent::__construct($client);
  }

  /**
   * Send mailing with provided data.
   *
   * Application authentication key and ID must be set.
   */
  public function create(array $data): array
  {
    //Use resolver to make sure we get clean data
    //$resolvedData = $this->resolverFactory->createNotificationResolver()->resolve($data);

    $request = $this->createRequest('POST', '/mailings');
    $request = $request->withHeader('Authorization', "Bearer {$this->client->getConfig()->getApiToken()}");
    $request = $request->withHeader('Content-Type', 'application/json');
    $request = $request->withBody($this->createStream($data));

    return $this->client->sendRequest($request);
  }
}