<?php

interface abstract_packet
{
  // Check if filled data represent that packed
  public function Detect($packed_data);

  public function Encode($data);
  public function Decode($packed_data);

  public static function not_implemented($packet, $method)
  {
    throw new \Exception("Packet {$packet} has not {$method} implementation");
  }
}
