<?php
namespace zyzo\MeteorDDP\socket;

require('AbstractSocketPipe.php');

class WebSocketPipe extends AbstractSocketPipe
{
  private $sock;

  public function open($address) {
    $this->sock = new WebSocket\Client($address);
  }

  public function Close() {
    if (!$this->IsClosed())
      return;

    $this->sock->close();
    $this->sock = null;
  }

  public function Write($data) {
    if (!$this->IsValid())
      return;

    $this->sock->send($data);
  }

  public function Read($chunk_size = AbstractSocketPipe::CHUNK_SIZE) {
    return $this->sock->read(true);
  }

  public function IsValid() {
    if ($this->IsClosed())
      return false;

    return $this->isConnected();
  }

  public function IsClosed() {
    return $this->sock === null;
  }
}
