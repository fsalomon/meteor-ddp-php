<?php
namespace synchrotalk\MeteorDDP;

require('vendor/autoload.php');
require('socket/WebSocketPipe.php');

class Client extends Reactor
{
  public static $default_logger = null;
  public static $log = [];

  public static function Log($system)
  {
    if (isset(self::$log[$system]))
      return self::$log[$system];

    if (is_null(self::$default_logger))
      self::$default_logger = new \Monolog\Logger("noname");

    return self::$log[$system]
      = self::$default_logger->withName("DDP_".$system);
  }

  private $herald;

  public function __construct($address = null, $sock = null)
  {
    if (is_null($sock))
      if (strpos($address, "sockjs") !== false)
        $sock = new socket\SockJSPipe();
      else
        $sock = new socket\WebSocketPipe();

    $parser = protocol\parser::ConstructDefaultParser();

    parent::__construct($sock, $parser);
    $this->herald = new Herald($sock, $parser);
    $this->herald->set_reactor($this);

    if (!is_null($address))
      $this->open($address);
  }

  public function open($address)
  {
    $this->sock->open($address);
  }

  public function close()
  {
    $this->sock->close();
  }

  public function get_result($id = null)
  {
    $this->react();

    $result = $this->get_component('result', $id);

    if (!is_null($result))
      $this->remove_component('result', $id);

    return $result;
  }

  public function __call($method, $args)
  {
    return call_user_func_array([$this->herald, $method], $args);
  }


  private $react = null;

  private function react()
  {
    $packet = $this->Read();
    if ($packet === null)
      return;

    Client::Log("client")->addInfo("Received {$packet->type} event");
    $this->on($packet->type, $packet->value);
  }

  private function on($type, $value)
  {
    if (is_null($this->react))
      $this->react = React::constructDefaultReact($this);

    return $this->react->$type($value);
  }
}
