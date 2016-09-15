<?php
namespace synchrotalk\MeteorDDP\protocol\packets;

class initial_packet extends abstract_packet
{
  public function Detect($packed_data)
  {
    return
      !isset($packed_data['msg'])
      && isset($packed_data['server_id']);
  }

  public function Encode($data)
  {
    abstract_packet::not_implemented(__CLASS__, __METHOD__);
  }

  public function Decode($packed_data)
  {
    return [];
  }
}
