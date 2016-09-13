<?php

class reactor extends protocol/reader
{
  protected $reactor = [];

  public function react()
  {
    $packet = $this->Read();
    if ($packet === null)
      return;

    $this->react->on($packet->type, $packet->value);
  }

  public function on($type, $value)
  {
    return $this->react->$type($value);
  }
}
