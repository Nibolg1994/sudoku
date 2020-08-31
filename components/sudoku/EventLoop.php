<?php

namespace app\components\sudoku;

use app\components\sudoku\events\Event;
use app\components\sudoku\events\EventStartGameResponse;
use app\components\sudoku\events\EventMoveRequest;
use app\components\sudoku\events\EventMoveResponse;
use app\components\sudoku\events\EventTopListResponse;
use app\models\CompetitiveSudoku;
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
     * @var CompetitiveSudoku
     */
    private $game;

    /**
     * ApplicationLoop constructor.
     * @param CompetitiveSudoku $game
     */
    public function __construct(CompetitiveSudoku $game)
    {
        parent::__construct();
        $this->isFinished = false;
        $this->game = $game;
    }

    /**
     * @param Event $event
     * @return void
     */
    public function startGame(Event $event)
    {
        if ($this->isFinished) {
            $this->isFinished = false;
            $this->game->restart();
        }

        $this->game->connect($event->user);

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
        $result = $this->game->move(
            $event->user,
            $event->cellId,
            $event->value
        );

        if ($result) {
            $responseEvent = new EventMoveResponse();
            $responseEvent->idCell = $event->cellId;
            $responseEvent->value = $event->value;

            $this->trigger(
                ServerApplicationInterface::EVENT_MOVE_RESPONSE,
                $responseEvent
            );
        }
    }

    /**
     * @param Event $event
     * @return void
     */
    public function topList(Event $event)
    {
        $storage = $this->game->getStorage();
        $responseEvent = new EventTopListResponse();
        $responseEvent->user = $event->user;
        $responseEvent->topList = $storage->getTopList();

        $this->trigger(
            ServerApplicationInterface::EVENT_SHOW_TOP_LIST_RESPONSE,
            $responseEvent
        );
    }
}