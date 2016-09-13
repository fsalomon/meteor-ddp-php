<?php

class reader extends io_interface
{
  public function Read()
  {
    $packet = $this->sock->Read();

    // No packet yet ready
    if ($packet == null)
      return null;

    $packed = json_decode($packet);
    $parsed_packet = $this->parcer->DecodePacket($packed);

    return $parsed_packet;
  }
}
