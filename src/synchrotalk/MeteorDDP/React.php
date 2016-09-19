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

  private function onPing($message)
  {
    $id = @$message['id'];
    $this->client->pong($id);
  }

  private function onResult($message)
  {
    if ($message['msg'] == 'updated')
      return; // ignore updated wddx_packet_start()

    $component = $this->client->get_component('rpc', $message['id']);
    $this->client->remove_component('rpc', $message['id']);

    if (is_callable($component['cb']))
      return $component['cb']($message);

    $this->client->add_component('result', $message['result'], $message['id']);
  }

  private function onCollectionAdded($message)
  {
    // They are similar
    $this->onCollectionChanged($message);
  }

  private function onCollectionChanged($message)
  {
    $collection = &$this->client->get_component('collection', $message['collection']);

    $fields = &$collection['data'][$message['id']];

    if (isset($message['fields']))
      foreach (@$message['fields'] as $name => $value)
        $fields[$name] = $value;

    if (isset($message['cleared']))
      foreach (@$message['cleared'] as $name)
        unset($fields[$name]);
  }

  private function onCollectionRemoved($message)
  {
    $collection = &$this->client->get_component('collection', $message['collection']);

    unset($collection['data'][$message['id']]);
  }

  private function onCollectionReady($message)
  {
    foreach ($message['subs'] as $id)
      $this->SetCollectionReady($id);
  }

  private function SetCollectionReady($id)
  {
    $sub = $this->client->get_component('sub', $id);
    $name = $sub['name'];

    $collection = &$this->client->get_component('collection', $name);
    $collection['ready'] = true;

    if (is_callable($sub['cb']))
      $sub['cb']($name, $collection);
  }

  private function onCollection($message)
  {
    Client::Log('react')->addDebug($message['msg']);

    switch ($message['msg'])
    {
    case 'added':
      return $this->onCollectionAdded($message);
    case 'changed':
      return $this->onCollectionChanged($message);
    case 'removed':
      return $this->onCollectionRemoved($message);
    case 'ready':
      return $this->onCollectionReady($message);
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
