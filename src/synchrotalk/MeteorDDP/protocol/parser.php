<?php
namespace synchrotalk\MeteorDDP\protocol;

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

    if (is_null($result) && $fatal)
      throw new \Exception("Internal issue : Unable to encode $type data");

    return $result;
  }

  public function DecodePacket($packed_data)
  {
    foreach (array_keys($this->known_packets) as $type)
    {
      $result = $this->Decode($type, $packed_data, false);
      if (is_null($result))
        continue;

      $parsed_packet = new parsed_packet();
      $parsed_packet->type = $type;
      $parsed_packet->value = $result;
      return $parsed_packet;
    }

    throw new \Exception("Internal issue : Unsupported packet type");
  }

  public function Decode($type, $data, $fatal = true)
  {
    $encoder = $this->GetEncoder($type);

    if (!$encoder->detect($data))
      if (!$fatal)
        return null;
      else
        throw new \Exception("Internal issue : Decoder mismatch on $type");

    $result = $encoder->decode($data);

    if (is_null($result) && $fatal)
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
    $parcer = new parser();

    $ddp_packets =
    [
      'connect' => 'connect_packet',
      'ping' => 'ping_packet',
      'pong' => 'pong_packet',
      'rpc' => 'rpc_packet',
      'sub' => 'sub_packet',
      'initial' => 'initial_packet',
    ];

    foreach ($ddp_packets as $name => $classname)
    {
      $class = "\\synchrotalk\\MeteorDDP\\protocol\\packets\\$classname";
      $parcer->RegisterPacket($name, new $class);
    }

    return $parcer;
  }
}
