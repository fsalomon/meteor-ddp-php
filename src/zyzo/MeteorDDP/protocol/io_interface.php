<?php

class io_interface
{
  protected $sock;
  protected $parcer;

  public function __construct($sock, $parcer)
  {
    $this->sock = $sock;
    $this->parcer = $parcer;
  }
}
