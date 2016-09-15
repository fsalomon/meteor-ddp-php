<?php
namespace synchrotalk\MeteorDDP\protocol\packets;

abstract class abstract_packet
{
  // Check if filled data represent that packed
  abstract public function Detect($packed_data);

  abstract public function Encode($data);
  abstract public function Decode($packed_data);

  public static function not_implemented($packet, $method)
  {
    throw new \Exception("Packet {$packet} has no {$method} implementation");
  }
}
