<?php
namespace zyzo\MeteorDDP\protocol\packets;

class sub_packet extends abstract_packet
{
  // Check if filled data represent that packed
  public function Detect($packed_data)
  {
    $events =
    [
      'added',
      'changed',
      'removed',
    ];

    return in_array(@$packed_data['msg'], $events);
  }

  public function Encode($data=null)
  {
    $packet =
    [
      "msg" => "sub",
      "name" => $data['name'],
      "id" => $data['id'],
    ];

    if (!empty($args))
      $packet['params'] = $data['args'];

    return $packet;
  }


  public function Decode($packed_data)
  {
    return $packed_data;
  }
}
