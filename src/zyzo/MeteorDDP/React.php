<?php
namespace zyzo\MeteorDDP;

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
    ];

    foreach ($actions as $event => $method)
      $react->addReaction($event, [$react, $method]);

    return $react;
  }

  public function __call($event, $arguments)
  {
    Client::Log('react')->addInfo("Reacting on $event");

    $method = $this->getReaction($event);
    return call_user_func_array($method, $arguments);
  }

  private function onPing($pingId)
  {
    $this->client->pong($pingId);
  }

  private function onResult($message)
  {
    $component = $this->get_component('rpc', $message->id);
    $this->remove_component('rpc', $message->id);

    if (is_callable($component['cb']))
      return $component['cb']($message);

    $this->add_component('result', $message, $message->id);
  }

  private function onAdded($message)
  {
    Client::Log('react')->addDebug("Added", $message);
    $this->add_component('collection', $message, $message->id);
  }

  private function onChanged($message)
  {
    Client::Log('react')->addDebug("Changed", $message);
    $this->add_component('collection', $message, $message->id);
  }

  private function onRemoved($message)
  {
    Client::Log('react')->addDebug("Removed", $message);
    $this->remove_component('collection', $message->id);
  }

  private function onReady($message)
  {
    // stub
  }

  private function ignore($message)
  {
  }

}
