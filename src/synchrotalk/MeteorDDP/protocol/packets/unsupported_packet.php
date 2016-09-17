<?php
namespace synchrotalk\MeteorDDP\protocol\packets;

// Just kidding it is out of protocol packet
class unsupported_packet extends abstract_packet
{
  public function Detect($packed_data)
  {
    return true;
  }

  public function Encode($data=null)
  {
    abstract_packet::not_implemented(__CLASS__, __METHOD__);
  }


  public function Decode($packed_data)
  {
    return $packed_data;
  }
}
