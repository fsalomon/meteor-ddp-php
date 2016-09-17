<?php
namespace synchrotalk\MeteorDDP\socket;
use synchrotalk\MeteorDDP\Client as Client;

// https://sockjs.github.io/sockjs-protocol/sockjs-protocol-0.3.3.html

class SockJSPipe extends WebSocketPipe
{
  private $sock;

  private $stored_data = [];

  public function Write($data)
  {
    $frame = json_encode([$data]);

    parent::Write($frame);
  }

  public function Read($chunk_size = AbstractSocketPipe::CHUNK_SIZE)
  {
    if (!empty($this->stored_data))
      return $this->ReturnData();

    $received = parent::Read($chunk_size);

    if (is_null($received))
      return null;

    return $this->ParseFrame($received);
  }

  public function ParseFrame($received)
  {
    $opcode = $received[0];

    $handler_name = "Frame{$opcode}";

    if (!method_exists($this, $handler_name))
      throw new \Exception("Internal issue : SocketJS meet unexpected frame $opcode");

    return $this->$handler_name(substr($received, 1));
  }

  // Opening frame
  public function FrameO($data)
  {
    return null;
  }

  // Heartbeat frame
  public function FrameH($data)
  {
    return null;
  }

  // Data frame
  public function FrameA($data)
  {
    $this->stored_data = json_decode($data);

    return $this->ReturnData();
  }

  // Close frame
  public function FrameC($data)
  {
    $reason = json_decode($data);

    $return =
    [
      "code" => $reason_array[0],
      "message" => $reason_array[1],
    ];

    return json_encode($return);
  }

  public function ReturnData()
  {
    return array_pop($this->stored_data);
  }
}
