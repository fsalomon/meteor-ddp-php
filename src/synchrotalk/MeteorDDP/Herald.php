<?php
namespace synchrotalk\MeteorDDP;

class herald extends protocol\writer
{
  private $reactor;

  public function set_reactor(&$reactor)
  {
    $this->reactor = &$reactor;
  }

  /**
   * This function creates a DDP connection on top of the WebSocket channel.
   * This must be called before the client could invoke server's method.
   * @param int $version
   * @param array $supportedVersions
   */
  public function connect($version = 1, $supportedVersions = array(1))
  {
    $packet =
    [
      'version' => $version,
      'supported' => $supportedVersions,
    ];

    $this->Write('connect', $packet);
  }

  /**
   * Synchronous Meteor.call. Use DDPClient::getResult to poll the return value
   * @param $method
   * @param $args
   */
  public function call($method, $args, $cb = null)
  {
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
  }

  /**
   * @param $name
   * @param array $args
   */
  public function subscribe($name, $args = array(), $cb = null) {
    $component =
    [
      'cb' => $cb,
    ];

    $id = $this->reactor->add_component('sub', $component);

    $packet =
    [
      'name' => $name,
      'args' => $args,
      'id' => $id,
    ];

    $this->Write('sub', $packet);
  }
}
