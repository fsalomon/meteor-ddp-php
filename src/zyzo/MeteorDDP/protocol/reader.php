<?php

class reader extends io_interface
{
  public function Read()
  {
    $packet = $this->sock->Read();

    $packed = json_decode($packet);
    $parsed_packet = $this->parcer->DecodePacket($packed);

    return $parsed_packet;
  }
}
