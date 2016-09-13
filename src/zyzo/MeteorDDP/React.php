<?php

class React
{
  private $known_events = [];

  public function __call($event, $arguments)
  {
  }

  private function onPing($pingId)
  {
      $this->sender->pong($pingId);
  }

  private function onResult($message)
  {
      $this->results[$message->id] = $message->result;
  }

  private function onAdded($message)
  {
      $this->mongoAdapter->insertOrUpdate(
          $message->collection,
          $message->id,
          isset($message->fields) ? $message->fields : null);
  }

  private function onChanged($message)
  {
      $this->mongoAdapter->update(
          $message->collection,
          $message->id,
          isset($message->fields) ? $message->fields : null,
          isset($message->cleared) ? $message->cleared : null);
  }

  private function onRemoved($message)
  {
      $this->mongoAdapter->remove($message->collection, $message->id);
  }

  private function onReady($message)
  {
      // stub
  }

}
