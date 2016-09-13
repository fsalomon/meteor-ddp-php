<?php

class React
{
  private $known_events = [];
  private $client;

  public function __construct(&$client)
  {
    $this->client = $client;
  }

  public function __call($event, $arguments)
  {
  }

  private function onPing($pingId)
  {
    $this->client->pong($pingId);
  }

  private function onResult($message)
  {
    $component = $this->get_component('rpc', $message->id);
    $this->remove_component('rpc', $message->id);

    if (is_callable($component['cb']))
      return $component['cb']($message);

    $this->add_component('result', $message, $message->id);
  }

  private function onAdded($message)
  {
    $this->add_component('collection', $message, $message->id);
  }

  private function onChanged($message)
  {
    $this->add_component('collection', $message, $message->id);
  }

  private function onRemoved($message)
  {
    $this->remove_component('collection', $message->id);
  }

  private function onReady($message)
  {
      // stub
  }

}
