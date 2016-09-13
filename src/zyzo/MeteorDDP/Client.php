<?php

require('socket/WebSocketPipe.php');

class Client
{
  private $pipe;

  public function __construct($address = null)
  {
    $this->pipe = new socket/WebSocketPipe();

    if (!is_null($address))
      $this->connect($address);
  }

  public function open($address)
  {
    $this->pipe->open($address);
  }

  public function close()
  {
    $this->pipe->close();
  }

  private $react;

  public function on($packet_type, $packet)
  {
    $this->react->$packet_type($packet);
  }

  public function get_result($id)
  {

  }
}
