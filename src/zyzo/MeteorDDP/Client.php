<?php

require('socket/WebSocketPipe.php');

class Client extends Reactor
{
  private $writer;

  public function __construct($address = null)
  {
    $sock = new socket/WebSocketPipe();
    $parcer = parser::ConstructDefaultParser();

    parent::__construct($sock, $parcer);
    $this->writer = new socket/writer($sock, $parcer);



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
}
