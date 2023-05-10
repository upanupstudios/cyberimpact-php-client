<?php

namespace Upanupstudios\Cyberimpact\Php\Client;

class Groups extends AbstractApi
{
  /**
   * Retrieve a paginated list of groups.
   */
  public function getAll($params = [])
  {
    //TODO: allow parameters to be passed
    //NOTE: limit - The amount of results per page (max: 10 000) Default: 20.
    $response = $this->client->request('GET', 'groups?limit=9999');

    return $response;
  }

  /**
   * Retrieve a specific group by title.
   */
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
   * Add a group.
   */
  public function add(array $data)
  {
    $options['body'] = json_encode($data);

    $response = $this->client->request('POST', 'groups', $options);

    return $response;
  }
