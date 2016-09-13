<?php

interface abstract_packet
{
  // Check if filled data represent that packed
  public function Detect($packed_data);

  public function Encode($data);
  public function Decode($packed_data);
}
