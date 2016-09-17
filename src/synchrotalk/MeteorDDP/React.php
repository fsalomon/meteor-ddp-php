<?php
namespace synchrotalk\MeteorDDP;

class React
{
  private $known_events = [];
  private $client;

  public function __construct(&$client)
  {
    $this->client = $client;
  }

  public function addReaction($event, $method)
  {
    Client::Log('react')->addNotice("Register $event handler");
    $this->known_events[$event] = $method;
  }

  private function getReaction($event)
  {
    if (!isset($this->known_events[$event]))
    {
      Client::Log('react')->addCritical("Unknown $event handler");
      throw new \Exception("Internal issue : cant react to unknown $event event");
    }

    return $this->known_events[$event];
  }

  public static function constructDefaultReact(&$client)
  {
    $react = new React($client);

    $actions =
    [
      'ping' => 'onPing',
      'rpc' => 'onResult',
      'collection' => 'onCollection',
      'initial' => 'ignore',
      'connect' => 'ignore',
      'sub' => 'echondie',
      'unsupported' => 'echondie',
    ];

    foreach ($actions as $event => $method)
      $react->addReaction($event, [$react, $method]);

    return $react;
  }

  public function __call($event, $arguments)
  {
    Client::Log('react')->addDebug("Reacting on $event");

    $method = $this->getReaction($event);
    return call_user_func_array($method, $arguments);
  }

  private function onPing($pingId)
  {
    $this->client->pong($pingId);
  }

  private function onResult($message)
  {
    if ($message['msg'] == 'updated')
      return; // ignore updated packets

    $component = $this->client->get_component('rpc', $message['id']);
    $this->client->remove_component('rpc', $message['id']);

    if (is_callable($component['cb']))
      return $component['cb']($message);

    $this->client->add_component('result', $message, $message['id']);
  }

  /*
    Disclaimer: this is forcing client application to work with ID
    Subscribe list should contain id -> name vocabulary
    And collections should be ordered by name

    To altering collection we should use id -> name -> collection path
   */

  private function onAdded($message)
  {
    Client::Log('react')->addDebug("Added");
    $this->client->add_component('collection', $message['fields'], $message['id']);
  }

  private function onChanged($message)
  {
    $this->echondie($message);
    Client::Log('react')->addDebug("Changed");
    $this->client->add_component('collection', $message['fields'], $message['id']);
  }

  private function onRemoved($message)
  {
    Client::Log('react')->addDebug("Removed");
    $this->client->remove_component('collection', $message['id']);
  }

  private function onCollection($message)
  {
    switch ($message['msg'])
    {
    case 'added':
      return $this->onAdded($message);
    case 'changed':
      return $this->onChanged($message);
    case 'removed':
      return $this->onRemoved($message);
    default:
      $this->echondie($message);
    }
  }

  private function ignore($message)
  {
  }

  private function echo($message)
  {
    var_dump($message);
  }

  private function echondie($message)
  {
    $this->echo($message);
    die();
  }

}
