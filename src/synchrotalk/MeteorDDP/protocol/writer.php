<?php
namespace synchrotalk\MeteorDDP\protocol;

class writer extends io_interface
{
  public function Write($type, $data)
  {
    $packed = $this->parser->Encode($type, $data);

    // DDP using json
    $packet = json_encode($packed);

    $this->sock->Write($packet);
  }
}
