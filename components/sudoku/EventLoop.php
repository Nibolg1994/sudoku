<?php

namespace app\components\sudoku;

use app\components\sudoku\events\Event;
use app\components\sudoku\events\EventStartGameResponse;
use app\components\sudoku\events\EventMoveRequest;
use app\components\sudoku\events\EventMoveResponse;
use app\components\sudoku\events\EventTopListResponse;
use app\models\SudokuGame;
use app\models\SudokuStorageInterface;
use app\models\User;
use UserRepository;
use yii\base\Component;

/**
 * Class ServerApplicationEventLoop
 */
class EventLoop extends Component implements ServerApplicationInterface
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
     * @var User
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
    public function startGame(Event $event)
    {
        if ($this->isFinished) {
            $this->isFinished = false;
            $this->restart();
        }

        UserRepository::add($event->user);

        $responseEvent = new EventStartGameResponse();
        $responseEvent->board = $this->game->getBoard();

        $this->trigger(
            ServerApplicationInterface::EVENT_START_GAME_ACCEPT,
            $responseEvent
        );
    }


    /**
     * @param EventMoveRequest $event
     * @return void
     */
    public function move(EventMoveRequest $event)
    {
        if ($this->isFinished) {
            return;
        }

        $cellId = $event->cellId;
        $value = $event->value;
        $user = $event->user;

        if (
            !empty($this->moves[$cellId])
            && $user->id != $this->moves[$cellId]
        ) {
            return;
        }

        if (!$this->game->move($cellId, $value)) {
            return;
        }

        $this->lastUser = $user;
        $this->moves[$cellId] = $user->id;

        $responseEvent = new EventMoveResponse();
        $responseEvent->idCell = $cellId;
        $responseEvent->value = $value;

        $this->finish();

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
        $responseEvent = new EventTopListResponse();
        $responseEvent->user = $event->user;
        $responseEvent->topList = $storage->getTopList();

        $this->trigger(
            ServerApplicationInterface::EVENT_SHOW_TOP_LIST_RESPONSE,
            $responseEvent
        );
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
}