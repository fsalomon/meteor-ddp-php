<?php
namespace synchrotalk\MeteorDDP;

class reactor extends protocol\reader
{
  protected $reactor = [];

  public function add_component($type, $component, $id = null)
  {
    if (is_null($id))
      $id = $this->get_commonent_id($type);

    Client::Log('reactor')->addNotice("Adding component");
    Client::Log('reactor')->addInfo("$type $id");

    $this->reactor[$type][$id] = $component;

    return $id;
  }

  private function get_commonent_id($type)
  {
    $last_id = $this->get_component('component_ids', $type);

    if ($last_id === null)
      $last_id = 0;

    $last_id++;

    $this->add_component('component_ids', $last_id, $type);

    return (string)$last_id;
  }

  public function &get_component($type, $id)
  {
    if (!isset($this->reactor[$type][$id]))
      $this->reactor[$type][$id] = null;

    $we_love_php = &$this->reactor[$type][$id];
    return $we_love_php;
  }

  public function remove_component($type, $id)
  {
    Client::Log('reactor')->addNotice('Removing component');
    Client::Log('reactor')->addInfo("$type $id");

    unset($this->reactor[$type][$id]);
  }

  public function reactor_checkpoint()
  {
    return $this->reactor;
  }

  public function reactor_restore($checkpoint)
  {
    $this->reactor = $checkpoint;
  }
}
