<?php
namespace synchrotalk\MeteorDDP;

class reactor extends protocol\reader
{
  protected $reactor = [];
  private $component_ids = [];

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
    if (!isset($this->component_ids[$type]))
      $this->component_ids[$type] = 1;

    return (string)$this->component_ids[$type]++;
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
}
