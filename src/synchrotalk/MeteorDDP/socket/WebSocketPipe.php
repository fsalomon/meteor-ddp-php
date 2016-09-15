<?php
namespace synchrotalk\MeteorDDP\socket;

require('AbstractSocketPipe.php');

class WebSocketPipe extends AbstractSocketPipe
{
  private $sock;

  public function open($address) {
    $this->sock = new \WebSocket\Client($address);
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

    Client::Log('websocket')->addInfo('Sending', $data);

    $this->sock->send($data);
  }

  public function Read($chunk_size = AbstractSocketPipe::CHUNK_SIZE) {
    $received =  $this->sock->receive(true);

    if (!is_null($received))
      Client::Log('WebSocket')->addInfo('Receiving', $received);

    return $received;
  }

  public function IsValid() {
    if ($this->IsClosed())
      return false;

    return $this->sock->isConnected();
  }

  public function IsClosed() {
    return $this->sock === null;
  }
}
