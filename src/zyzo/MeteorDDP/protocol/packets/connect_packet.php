<?php

class connect_packet implements abstract_packet
{
  public function Detect($packed_data)
  {
    return false;
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
    abstract_packet::not_implemented(__CLASS__, __METHOD__);
  }
}
