<?php
namespace synchrotalk\MeteorDDP\protocol\packets;

class connect_packet extends abstract_packet
{
  public function Detect($packed_data)
  {
    return @$packed_data['msg'] == 'connected';
  }

  public function Encode($data)
  {
    if (empty($data['supported']))
      $data['supported'] = [$data['supported']];

    $packet =
    [
      "msg" => "connect",
      "version" => (string)$data['version'],
      "support" => array_map(function ($element)
      {
        return (string)$element;
      }, $data['supported']),
    ];

    return $packet;
  }

  public function Decode($packed_data)
  {
    var_dump($packed_data);
    die();
    abstract_packet::not_implemented(__CLASS__, __METHOD__);
  }
}
