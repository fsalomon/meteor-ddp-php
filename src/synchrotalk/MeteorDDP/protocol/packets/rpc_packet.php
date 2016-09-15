<?php
namespace synchrotalk\MeteorDDP\protocol\packets;

class rpc_packet extends abstract_packet
{
  public function Detect($packed_data)
  {
    return @$packed_data['msg'] == 'result';
  }

  public function Encode($data=null)
  {
    $packet =
    [
      "msg" => "method",
      "method" => $data['method'],
      "params" => $data['args'],
      "id" => $data['id'],
    ];


    return $packet;
  }

  public function Decode($packed_data)
  {
    return $packed_data;
  }
}
