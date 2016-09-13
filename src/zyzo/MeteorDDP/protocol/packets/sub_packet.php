<?php

class sub_packet implements abstract_packet
{
  // Check if filled data represent that packed
  public function Detect($packed_data);

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


  public function Decode($packed_data);
}
