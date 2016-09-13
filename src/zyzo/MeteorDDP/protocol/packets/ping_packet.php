<?php

class ping_packet implements abstract_packet
{
  public function Detect($packed_data)
  {
    return $packed_data['msg'] == 'ping';
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
