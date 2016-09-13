<?php

class reactor extends protocol/reader
{
  protected $reactor = [];
  private $component_ids = [];

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

  public function add_component($type, $component)
  {
    $id = $this->get_commonent_id($type);

    $this->reactor[$type][$id] = $component;

    return $id;
  }

  private function get_commonent_id($type)
  {
    if (!isset($this->component_ids[$type]))
      $this->component_ids[$type] = 0;

    return $this->component_ids[$type];
  }
}
