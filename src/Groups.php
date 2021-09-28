<?php

namespace Upanupstudios\Cyberimpact\Php\Client;

class Groups extends AbstractApi 
{
  public function __construct(Cyberimpact $client)
  {
    parent::__construct($client);
  }

  /**
   * Get all groups.
   *
   * User authentication key must be set.
   */
  public function getAll($params = []): array
  {
    //TODO: allow parameters to be passed
    //NOTE: limit - The amount of results per page (max: 10 000) Default: 20.
    $request = $this->createRequest('GET', '/groups?limit=9999');
    $request = $request->withHeader('Authorization', "Bearer {$this->client->getConfig()->getApiToken()}");

    return $this->client->sendRequest($request);
  }

  public function getByTitle($title)
  {
    $groups = $this->getAll();
    
    if(!empty($groups['groups'])) {
      foreach($groups['groups'] as $group) {
        if($group['title'] == $title) {
          return $group;
        }
      }
    }

    return false;
  }

  /**
   * Send new notification with provided data.
   *
   * Application authentication key and ID must be set.
   */
  public function add(array $data): array
  {
    //Use resolver to make sure we get clean data
    //$resolvedData = $this->resolverFactory->createNotificationResolver()->resolve($data);

    $request = $this->createRequest('POST', '/groups');
    $request = $request->withHeader('Authorization', "Bearer {$this->client->getConfig()->getApiToken()}");
    $request = $request->withHeader('Content-Type', 'application/json');
    $request = $request->withBody($this->createStream($data));

    return $this->client->sendRequest($request);
  }
}