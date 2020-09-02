<?php

namespace app\components\sudoku;

use app\components\sudoku\events\ErrorEvent;
use app\components\sudoku\events\Event;
use app\models\SudokuGame;
use app\models\SudokuStorageInterface;
use app\models\User;
use app\repositories\UserRepository;


/**
 * Class ServerApplicationEventLoop
 */
class EventLoop extends ApplicationServer
{
    /**
     * @var bool
     */
    private $isFinished;

    /**
     * @var SudokuGame
     */
    private $game;

    /**
     * @var
     */
    private $lastUser;

    /**
     * @var array
     */
    private $moves = [];

    /**
     * @var SudokuStorageInterface
     */
    private $storage;

    /**
     * ApplicationLoop constructor.
     * @param SudokuGame $game
     * @param SudokuStorageInterface $storage
     */
    public function __construct(SudokuGame $game, SudokuStorageInterface $storage)
    {
        parent::__construct();
        $this->isFinished = false;
        $this->game = $game;
        $this->storage = $storage;
    }

    /**
     * @param Event $event
     * @return void
     */
    public function start(Event $event)
    {
        if ($this->isFinished) {
            $this->isFinished = false;
            $this->restart();
        }

        if (!isset($event->clientId, $event->eventData['name'])
            || !$this->connect($event->clientId, $event->eventData['name'])
        ) {
            $responseEvent = new ErrorEvent();
            $responseEvent->clientId = $event->clientId;
            $responseEvent->message = "Name already exists in this game or incorrect";
            $this->trigger(
                ServerApplicationInterface::EVENT_ERROR,
                $responseEvent
            );
            return;
        }

        $responseEvent = new Event();
        $responseEvent->eventData['game'] = $this->game->getBoard();
        $responseEvent->clientId = $event->clientId;

        $this->trigger(
            ServerApplicationInterface::EVENT_START_GAME_ACCEPT,
            $responseEvent
        );
    }


    /**
     * @param Event $event
     * @return void
     */
    public function move(Event $event)
    {
        if ($this->isFinished) {
            return;
        }

        if (!isset(
            $event->eventData['cellId'],
            $event->eventData['value'],
            $event->clientId)
        ) {
            return;
        }

        $cellId = $event->eventData['cellId'];
        $value = $event->eventData['value'];
        $clientId = $event->clientId;


        if (
            !empty($this->moves[$cellId])
            && $clientId != $this->moves[$cellId]
        ) {
            return;
        }

        if (!$this->game->move($value, $cellId)) {
            return;
        }

        $this->lastUser = $clientId;
        $this->moves[$cellId] = $clientId;
        $this->finish();

        $responseEvent = new Event();
        $responseEvent->clientId = $clientId;
        $responseEvent->eventData['cellId'] = $cellId;
        $responseEvent->eventData['value'] = $value;
        $this->trigger(
            ServerApplicationInterface::EVENT_MOVE_RESPONSE,
            $responseEvent
        );


    }

    /**
     * @param Event $event
     * @return void
     */
    public function topList(Event $event)
    {
        $storage = $this->storage;
        $responseEvent = new Event();
        $responseEvent->clientId = $event->clientId;
        $responseEvent->eventData['topList'] = $storage->getTopList();

        $this->trigger(
            ServerApplicationInterface::EVENT_SHOW_TOP_LIST_RESPONSE,
            $responseEvent
        );
    }

    /**
     * @param Event $event
     */
    public function disconnect(Event $event)
    {
        UserRepository::remove($event->clientId);
    }

    /**
     * @return bool
     */
    private function finish()
    {
        if (!$this->game->isEnd()) {
            return false;
        }

        $this->isFinished = true;
        $this->storage->save($this->lastUser);

        return true;
    }


    /**
     * Restart game
     */
    private function restart()
    {
        $this->game->restart();
        $this->lastUser = null;
        $this->moves[] = [];
        UserRepository::clear();
    }


    /**
     * @param $clientId
     * @param $name
     * @return bool
     */
    private function connect($clientId, $name)
    {
        if (!UserRepository::exists($clientId)
            && UserRepository::hasName($name)
        ) {
            return false;
        }

        $user = new User([
            'id' => $clientId,
            'name' => $name
        ]);

        if (!$user->validate()) {
            return false;
        }

        UserRepository::add($user);

        return true;
    }
}