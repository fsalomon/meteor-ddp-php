<?php

class rpc_packet implements abstract_packet
{
  // Check if filled data represent that packed
  public function Detect($packed_data);

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


  public function Decode($packed_data);
}
