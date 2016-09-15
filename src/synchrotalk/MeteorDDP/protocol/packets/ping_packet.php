<?php
namespace synchrotalk\MeteorDDP\protocol\packets;

class ping_packet extends abstract_packet
{
  public function Detect($packed_data)
  {
    return @$packed_data['msg'] == 'ping';
  }

  public function Encode($data=null)
  {
    return
    [
      "msg" => "ping",
    ];
  }


  public function Decode($packed_data)
  {
    return $packed_data;
  }
}
