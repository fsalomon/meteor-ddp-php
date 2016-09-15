<?php
namespace zyzo\MeteorDDP\protocol;

class reader extends io_interface
{
  public function Read()
  {
    $packet = $this->sock->Read();

    // No packet yet ready
    if ($packet == null)
      return null;

    $packed = json_decode($packet, true);
    $parsed_packet = $this->parser->DecodePacket($packed);

    return $parsed_packet;
  }
}
