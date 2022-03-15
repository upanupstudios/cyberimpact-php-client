<?php

namespace Upanupstudios\Cyberimpact\Php\Client;

class Mailings extends AbstractApi
{
  /**
   * Create a new mailing scheduled to be sent.
   */
  public function create(array $data)
  {
    $options['body'] = json_encode($data);

    $response = $this->client->request('POST', 'mailings', $options);

    return $response;
  }
}