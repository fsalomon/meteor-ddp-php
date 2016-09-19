<?php
namespace synchrotalk\MeteorDDP;

class herald extends protocol\writer
{
  private $reactor;
  private $connection_established = false;

  public function set_reactor(&$reactor)
  {
    $this->reactor = &$reactor;
  }

  private function require_connect()
  {
    if ($this->connection_established)
      return;

    $this->connect();
  }

  public function connect($version = 1, $supportedVersions = array(1))
  {
    $this->connection_established = true;

    $packet =
    [
      'version' => $version,
      'supported' => $supportedVersions,
    ];

    $this->Write('connect', $packet);
  }

  public function __call($method, $args)
  {
    $normalize_methods =
    [
      'call',
      'subscribe',
    ];

    if (!in_array($method, $normalize_methods))
      throw new \Exception("DDP::Client doesn't have '$method' method");

    if (count($args) == 2 && is_callable($args[1]))
      $args = [$args[0], [], $args[1]];

    return call_user_func_array([$this, $method], $args);
  }


  /**
   * Meteor.call($method, [[$args], $cb])
   * If cb is undefined - use getResult to receive answer
   * If args undefined - only 2 params may be used
   */
  private function call($method, $args = [], $cb = null)
  {
    $this->require_connect();

    $component =
    [
      'cb' => $cb,
    ];

    $id = $this->reactor->add_component('rpc', $component);

    $packet =
    [
      'method' => $method,
      'args' => $args,
      'id' => $id,
    ];

    $this->Write('rpc', $packet);

    return $id;
  }

  /**
   * Meteor.subscribe($collection, [[$args], $cb])
   * If cb is undefined - use Client::getCollection
   * If args undefined - only 2 params may be used
   * If cb is defined - it will be invoked when collection ready
   */
  private function subscribe($name, $args = array(), $cb = null)
  {
    $this->require_connect();

    $sub =
    [
      'cb' => $cb,
      'name' => $name,
    ];

    $id = $this->reactor->add_component('sub', $sub);

    $collection =
    [
      'ready' => false,
      'data' => [],
    ];
    $this->reactor->add_component('collection', $collection, $name);

    $packet =
    [
      'name' => $name,
      'args' => $args,
      'id' => $id,
    ];

    $this->Write('sub', $packet);

    return $id;
  }

  public function ping()
  {
    throw new \Exception("DDP::Client i was lazy to implement ping, please do it for me");
  }

  public function pong($id = null)
  {
    $packet = null;

    if (isset($id))
      $packet = ['id' => $id];

    $this->Write('pong', $packet);
  }
}
