<?php
namespace synchrotalk\MeteorDDP\protocol\packets;

class sub_packet extends abstract_packet
{
  public function Detect($packed_data)
  {
    return @$packed_data['msg'] == 'sub';
  }

  public function Encode($data=null)
  {
    $packet =
    [
      "msg" => "sub",
      "name" => $data['name'],
      "id" => $data['id'],
    ];

    if (!empty($data['args']))
      $packet['params'] = $data['args'];

    return $packet;
  }


  public function Decode($packed_data)
  {
    return $packed_data;
  }
}
