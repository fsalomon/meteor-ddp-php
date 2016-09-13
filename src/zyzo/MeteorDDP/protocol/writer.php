<?php

class writer extends io_interface
{
  public function Write($type, $data)
  {
    $packed = $this->parcer->Encode($type, $data);

    // DDP using json
    $packet = json_encode($packed);

    $this->sock->Write($msg);
  }
}
