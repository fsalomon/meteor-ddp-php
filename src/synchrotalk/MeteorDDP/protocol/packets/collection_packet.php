<?php
namespace synchrotalk\MeteorDDP\protocol\packets;

class collection_packet extends abstract_packet
{
  // Check if filled data represent that packed
  public function Detect($packed_data)
  {
    $events =
    [
      'added',
      'changed',
      'removed',
      'ready',
    ];

    return in_array(@$packed_data['msg'], $events);
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
