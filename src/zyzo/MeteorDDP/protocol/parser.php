<?php

require_once('connect_packet.php');
require_once('ping_packet.php');
require_once('pong_packet.php');
require_once('rpc_packet.php');
require_once('sub_packet.php');

class parser
{
  public function RegisterPacket($name, $encoder)
  {
  }

  public function EncodePacket($data)
  {
  }

  public function DecodePacket($packed_data)
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
