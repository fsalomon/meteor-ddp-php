<?php

class pong_packet implements abstract_packet
{
  // Check if filled data represent that packed
  public function Detect($packed_data);

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


  public function Decode($packed_data);
}
