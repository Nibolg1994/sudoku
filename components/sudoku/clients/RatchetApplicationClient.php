<?php

namespace app\components\sudoku\clients;

use app\components\sudoku\events\BaseEvent;
use app\components\sudoku\events\Event;
use app\components\sudoku\events\EventFactory;
use app\components\sudoku\ServerApplicationInterface;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * Class WsApplicationClient
 * @package app\components\websockets
 */
class RatchetApplicationClient extends ApplicationClient implements MessageComponentInterface
{
    /**
     * @var array
     */
    protected $clients;

    /**
     * WsApplicationClient constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->clients = [];
    }

    /**
     * When a new connection is opened it will be passed to this method
     * @param  ConnectionInterface $conn The socket/connection that just connected to your application
     * @throws \Exception
     */
    function onOpen(ConnectionInterface $conn)
    {
        $this->clients[$conn->resourceId] = $conn;
    }

    /**
     * This is called before or after a socket is closed (depends on how it's closed).  SendMessage to $conn will not result in an error if it has already been closed.
     * @param  ConnectionInterface $conn The socket/connection that is closing/closed
     * @throws \Exception
     */
    function onClose(ConnectionInterface $conn)
    {
        $event = new Event();
        $event->clientId = $conn->resourceId;
        $this->trigger(ClientApplicationInterface::EVENT_DISCONNECT, $event);
        unset($this->clients[$conn->resourceId]);
    }

    /**
     * If there is an error with one of the sockets, or somewhere in the application where an Exception is thrown,
     * the Exception is sent back down the stack, handled by the Server and bubbled back up the application through this method
     * @param  ConnectionInterface $conn
     * @param  \Exception $e
     * @throws \Exception
     */
    function onError(ConnectionInterface $conn, \Exception $e)
    {
        Yii::error($e->getMessage(). PHP_EOL. $e->getTraceAsString());
        $conn->close();
    }

    /**
     * Triggered when a client sends data through the socket
     * @param  \Ratchet\ConnectionInterface $from The socket/connection that sent the message to your application
     * @param  string $msg The message received
     * @throws \Exception
     */
    function onMessage(ConnectionInterface $from, $msg)
    {
        $event = EventFactory::createEvent($msg, $from->resourceId);
        if ($event) {
            $this->trigger($event->name, $event);
        }
    }

    /**
     * @param BaseEvent $event
     * @return void
     */
    public function sendEvent(BaseEvent $event)
    {
        if (ArrayHelper::keyExists($event->clientId, $this->clients)) {
            $client = $this->clients[$event->clientId];
            $client->send($event);
        }
    }

    /**
     * @param BaseEvent $event
     * @return void
     * @throws \Exception
     */
    public function sendBroadcastEvent(BaseEvent $event)
    {
        foreach (array_keys($this->clients) as $clientId) {
            if ($event->clientId !== $clientId) {
                // The sender is not the receiver, send to each client connected
                $this->clients[$clientId]->send($event);
            }
        }
    }
}