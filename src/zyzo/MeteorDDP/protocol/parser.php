<?php

require_once('packets/connect_packet.php');
require_once('packets/ping_packet.php');
require_once('packets/pong_packet.php');
require_once('packets/rpc_packet.php');
require_once('packets/sub_packet.php');
require_once('parsed_packet.php');

class parser
{
  public function RegisterPacket($name, $encoder)
  {
  }

  public function EncodePacket($data)
  {
  }

  public function Encode($type, $data, $fatal = true)
  {
  }

  public function DecodePacket($packed_data)
  {
  }

  public function Decode($type, $data, $fatal = true)
  {
  }

  public static function ConstructDefaultParser()
  {
    $parcer = new parcer();

    $ddp_packets =
    [
      'connect' => 'connect_packet',
      'ping' => 'ping_packet',
      'pong' => 'pong_packet',
      'rpc' => 'rpc_packet',
      'sub' => 'sub_packet',
    ];

    foreach ($ddp_packets as $name => $class)
      $parcer->RegisterPacket($name, new $class);

    return $parcer;
  }
}
