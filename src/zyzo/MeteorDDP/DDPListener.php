<?php
namespace zyzo\MeteorDDP;

use zyzo\MeteorDDP\enum\FrameStatus;

class DDPListener {
    private $pump;
    private $sender;
    private $wsClient;
    private $inDataFlow;

    private $unparsedBytes;

    /**
     * @param $client DDPClient
     * @param $sender DDPSender
     * @param $sock resource
     */
    public function __construct($client, $sender, $sock) {
        if (class_exists("\Thread"))
            $this->pump = new pump\PThreadsPump($sock);
        else
            $this->pump = new pump\BasePump($sock);

        $this->client = $client;
        $this->sender = $sender;
        $this->wsClient = new WebSocketClient();
        $this->unparsedBytes = "";
        $this->inDataFlow = false;
    }

    public function Start() {
        $this->pump->Start();
    }

    public function Stop() {
        $this->pump->Stop();
    }

    public function MicroRun() {
      $data = $this->pump->MicroRun();

      $this->unparsedBytes .= $data;

      if (!$this->inDataFlow)
        $this->enterDataFlow();
      else
        $this->process();
    }

    public function isRunning() {
      return $this->pump->isRunning();
    }

    private function enterDataFlow() {
        $data_begins = $this->wsClient->handshakeEnd($this->unparsedBytes);
        if ($data_begins === false)
            return;

        $this->unparsedBytes = substr($this->unparsedBytes, $data_begins);
        $this->inDataFlow = true;

        $this->process();
    }

    /**
     * @param $data
     * @return bool
     */
    private function process() {
        if (strlen($this->unparsedBytes) < 2)
            return;

        $wsClient = $this->wsClient;
        $status = $wsClient->validFrame($this->unparsedBytes);

        switch ($status->value()) {
            case (FrameStatus::NOT_VALID) :
                var_dump($this->unparsedBytes);
                throw new \Exception("Internal issue : Frame is not valid");
                break;
            case (FrameStatus::MISSING_END) :
                break;
            case (FrameStatus::VALID) :
            case (FrameStatus::EXCEED_END) :

                // doesn't work if invoke $this->wsClient->lastExceeds
                $exceedBytesNum = $wsClient->lastExceeds();

                if ($exceedBytesNum == 0) {
                    $valid_frame = $this->unparsedBytes;
                    $this->unparsedBytes = "";
                }
                else {
                    $valid_frame = substr($this->unparsedBytes, 0, -$exceedBytesNum);
                    $this->unparsedBytes = substr($this->unparsedBytes, -$exceedBytesNum, $exceedBytesNum);
                }

                $success = $this->processValidFrame($valid_frame);

                if (!$success) {
                    throw new \Exception('Internal issue : Unable to process DDP frame');
                }

                return $success;
        default:
            throw new \Exception("Internal issue : Unexpected frame status");
        }

        return false;
    }

    /**
     * @param $data
     * @return bool
     *       true if process successful
     */
    private function processValidFrame($data)
    {
        $json = $this->wsClient->draft10Decode($data);
        $parsed = json_decode($json->payload);
        if (json_last_error() === JSON_ERROR_NONE) {
            try {
                $this->client->onMessage($parsed);
            } catch (\Exception $e) {
                Utils::_error_log($e->getMessage() . PHP_EOL);
            }
        } else {
            return false;
        }
        return true;
    }
}
