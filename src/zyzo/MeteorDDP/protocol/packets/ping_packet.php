<?php

class ping_packet implements abstract_packet
{
  // Check if filled data represent that packed
  public function Detect($packed_data);

  public function Encode($data=null)
  {
    return
    [
      "msg" => "ping",
    ];
  }


  public function Decode($packed_data);
}
