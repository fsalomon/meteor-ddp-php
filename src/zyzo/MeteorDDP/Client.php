<?php

require('socket/WebSocketPipe.php');

class Client extends Reactor
{
  private $herald;

  public function __construct($address = null)
  {
    $sock = new socket/WebSocketPipe();
    $parcer = parser::ConstructDefaultParser();

    parent::__construct($sock, $parcer);
    $this->herald = new herald($sock, $parcer);
    $this->herald->set_reactor($this);

    if (!is_null($address))
      $this->open($address);
  }

  public function open($address)
  {
    $this->sock->open($address);
  }

  public function close()
  {
    $this->sock->close();
  }

  public function get_result($id)
  {
    $this->react();

    $result = $this->get_component('result', $id);

    if (!is_null($result)
      $this->remove_component('result', $id);

    return $result;
  }

  public function __call($method, $args)
  {
    return call_user_func_array([$this->herald, $method], $args);
  }


  private $react = null;

  private function react()
  {
    $packet = $this->Read();
    if ($packet === null)
      return;

    if (is_null($this->react))
      $this->react = new react($this);

    $this->react->on($packet->type, $packet->value);
  }

  private function on($type, $value)
  {
    return $this->react->$type($value);
  }
}
