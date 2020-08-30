<?php

namespace app\components\sudoku;

use app\components\sudoku\events\EventStartGameResponse;
use app\components\sudoku\events\EventMoveRequest;
use app\components\sudoku\events\EventMoveResponse;
use app\components\sudoku\events\EventRequest;
use app\components\sudoku\events\EventTopListResponse;
use app\models\CompetitiveSudoku;
use yii\base\Component;

/**
 * Class ApplicationEventLoop
 */
class ApplicationEventLoop extends Component
{
    /**
     *
     */
    const EVENT_START_GAME_REQUEST = 'startGameRequest';

    /**
     *
     */
    const EVENT_START_GAME_ACCEPT = 'startGameAccept';

    /**
     *
     */
    const EVENT_MOVE_REQUEST = 'eventMoveRequest';

    /**
     *
     */
    const EVENT_MOVE_RESPONSE = 'eventMoveRepose';

    /**
     *
     */
    const EVENT_SHOW_TOP_LIST_REQUEST = 'eventShowTopListRequest';

    /**
     *
     */
    const EVENT_SHOW_TOP_LIST_RESPONSE = 'eventShowTopListResponse';

    /**
     * @var bool
     */
    private $isFinised;

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
        $this->isFinised = false;
        $this->game = $game;
    }

    /**
     * @param EventRequest $event
     */
    public function startGame(EventRequest $event)
    {
        if ($this->isFinised) {
            $this->isFinised = false;
            $this->game->restart();
        }

        $this->game->connect($event->user);

        $responseEvent = new EventStartGameResponse();
        $responseEvent->board = $this->game->getBoard();

        $this->trigger(self::EVENT_START_GAME_ACCEPT, $responseEvent);
    }


    /**
     * @param EventMoveRequest $event
     */
    public function move(EventMoveRequest $event)
    {
        $result = $this->game->move(
            $event->user,
            $event->idCell,
            $event->value
        );

        if ($result) {
            $responseEvent = new EventMoveResponse();
            $responseEvent->idCell = $event->idCell;
            $responseEvent->value = $event->value;

            $users = $this->game->getUsers();
            unset($users[$event->user->id]);
            $responseEvent->users = $users;

            $this->trigger(self::EVENT_MOVE_RESPONSE, $responseEvent);
        }
    }


    /**
     * @param EventRequest $event
     */
    public function topList(EventRequest $event)
    {
        $storage = $this->game->getStorage();
        $responseEvent = new EventTopListResponse();
        $responseEvent->user = $event->user;
        $responseEvent->topList = $storage->getTopList();
        $this->trigger(self::EVENT_SHOW_TOP_LIST_RESPONSE, $responseEvent);
    }
}