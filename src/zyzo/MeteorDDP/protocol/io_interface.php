<?php
namespace zyzo\MeteorDDP\protocol;

class io_interface
{
  protected $sock;
  protected $parser;

  public function __construct($sock, $parser)
  {
    $this->sock = $sock;
    $this->parser = $parser;
  }
}
