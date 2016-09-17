<?php
namespace synchrotalk\MeteorDDP\protocol;

class reader extends io_interface
{
  public function Read()
  {
    $packet = $this->sock->Read();

    // No packet yet ready
    if ($packet == null)
      return null;


    if ($packet === 'o') // connection opened
      $packed = $packet;
    else
    {
      if ($packet[0] === 'a') // server answer
        $packet = substr($packet, 1); // shift left for json
      $packed = json_decode($packet, true);
    }

    $parsed_packet = $this->parser->DecodePacket($packed);

    return $parsed_packet;
  }
}
