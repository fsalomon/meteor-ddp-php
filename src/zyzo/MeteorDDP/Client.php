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

    if (!is_null($address))
      $this->open($address);
  }

  public function open($address)
  {
    $this->pipe->open($address);
  }

  public function close()
  {
    $this->pipe->close();
  }

  public function get_result($id)
  {

  }

  public function __call($method, $args)
  {
    return call_user_func_array([$this->herald, $method], $args);
  }
}
