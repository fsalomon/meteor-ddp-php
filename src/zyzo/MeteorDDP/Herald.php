<?php

class herald extends socket/writer
{
  /**
   * This function creates a DDP connection on top of the WebSocket channel.
   * This must be called before the client could invoke server's method.
   * @param int $version
   * @param array $supportedVersions
   */
  public function connect($version = 1, $supportedVersions = array(1))
  {
      $this->sender->connect($version, $supportedVersions);
  }

  /**
   * Synchronous Meteor.call. Use DDPClient::getResult to poll the return value
   * @param $method
   * @param $args
   */
  public function call($method, $args)
  {
      $this->sender->rpc($this->currentId, $method, $args);
      $this->methodMap[$method] = $this->currentId;
      $this->currentId++;
  }

  /**
   * @param $name
   * @param array $args
   */
  public function subscribe($name, $args = array()) {
      static $subId = 0;
      $this->sender->sub($subId++, $name, $args);
  }
}
