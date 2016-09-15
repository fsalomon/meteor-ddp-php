<?php
namespace synchrotalk\MeteorDDP\protocol\packets;

class pong_packet extends abstract_packet
{
  public function Detect($packed_data)
  {
    return @$packed_data['msg'] == 'pong';
  }


  public function Encode($data=null)
  {
    $packet =
    [
      "msg" => "pong",
    ];

    if ($data !== null)
      $packet['id'] = $data;

    return $packet;
  }

  public function Decode($packed_data)
  {
    return $packed_data;
  }
}
