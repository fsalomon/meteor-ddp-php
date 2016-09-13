<?php

class connect_packet implements abstract_packet
{
  // Check if filled data represent that packed
  public function Detect($packed_data);

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


  public function Decode($packed_data);
}
