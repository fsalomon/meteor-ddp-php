<?php

require_once('packets/abstract_packet.php');
require_once('packets/connect_packet.php');
require_once('packets/ping_packet.php');
require_once('packets/pong_packet.php');
require_once('packets/rpc_packet.php');
require_once('packets/sub_packet.php');
require_once('parsed_packet.php');

class parser
{
  private $known_packets = [];

  public function RegisterPacket($name, $encoder)
  {
    $this->known_packets[$name] = $encoder;
  }

  public function EncodePacket($data)
  {
    foreach (array_keys($this->known_packets) as $type)
    {
      $result = $this->Encode($type, $data, false);
      if (!is_null($result))
        return $result;
    }

    throw new \Exception("Internal issue : Unsupported packet type");
  }

  public function Encode($type, $data, $fatal = true)
  {
    $encoder = $this->GetEncoder($type);
    $result = $encoder->encode($data);

    if (!is_null($result) && $fatal)
      throw new \Exception("Internal issue : Unable to encode $type data");

    return $result;
  }

  public function DecodePacket($packed_data)
  {
    foreach (array_keys($this->known_packets) as $type)
    {
      $result = $this->Decode($type, $data, false);
      if (!is_null($result))
        return $result;
    }

    throw new \Exception("Internal issue : Unsupported packet type");
  }

  public function Decode($type, $data, $fatal = true)
  {
    $encoder = $this->GetEncoder($type);

    if (!$encoder->detect($data) && $fatal)
      throw new \Exception("Internal issue : Decoder mismatch on $type");

    $result = $encoder->decode($data);

    if (!is_null($result) && $fatal)
      throw new \Exception("Internal issue : Unable to dencode $type data");

    return $result;
  }

  private function GetEncoder($type)
  {
    if (!isset($this->known_packets[$type]))
      throw new \Exception("Internal issue : Encoder $type unknown");

    return $this->known_packets[$type];
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
      $parcer->RegisterPacket($name, new \protocol\packets\$class);

    return $parcer;
  }
}
