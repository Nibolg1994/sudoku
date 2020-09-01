<?php

namespace app\components\sudoku\clients;

use app\components\sudoku\events\Event;
use app\components\sudoku\events\EventFactory;
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
    public function __construct() {
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
        \UserRepository::remove($conn->resourseId);
        // The connection is closed, remove it, as we can no longer send it messages
        unset($this->clients[$conn->resourseId]);
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
        if (!$event) {
            return;
        }
        if (!$event->validate()) {
            $from->send(json_encode([
                'error' => true,
                'event' => $event->name
            ]));
        } else {
            $this->trigger($event->name, $event);
        }
    }

    /**
     * @param Event $event
     * @return void
     */
    public function sendEvent(Event $event)
    {
        if (ArrayHelper::keyExists($event->user->id, $this->clients)) {
            $client = $this->clients[$event->user->id];
            $client->send($event);
        }
    }

    /**
     * @param Event $event
     * @return void
     */
    public function sendBroadcastEvent(Event $event)
    {
        if (!ArrayHelper::keyExists($event->user->id, $this->clients)) {
            return;
        }
        $from = $this->clients[$event->user->id];
        foreach ($this->clients as $client) {
            if ($from !== $client) {
                // The sender is not the receiver, send to each client connected
                $client->send($event);
            }
        }
    }
}