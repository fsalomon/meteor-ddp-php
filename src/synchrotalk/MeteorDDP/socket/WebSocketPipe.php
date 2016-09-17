<?php
namespace synchrotalk\MeteorDDP\socket;
use synchrotalk\MeteorDDP\Client as Client;

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

    Client::Log('websocket')->addInfo('Sending', [$data]);

    $this->sock->send($data);
  }

  public function Read($chunk_size = AbstractSocketPipe::CHUNK_SIZE) {
    try
    {
      $received =  $this->sock->receive(true);
    } catch (\Exception $e)
    {
      Client::Log('WebSocket')->addError('Exception', [$e]);
      $this->Close();
      return null;
    }

    if (!is_null($received))
      Client::Log('WebSocket')->addInfo('Receiving', [$received]);

    return $received;
  }

  public function IsValid() {
    if ($this->IsClosed())
      return false;

    // Because WebSocket itself never flag on Exceptional connection
    return true;
  }

  public function IsClosed() {
    return $this->sock === null;
  }
}
