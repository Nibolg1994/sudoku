<?php

namespace app\components\sudoku;

use app\components\sudoku\events\EventMoveResponse;
use app\components\sudoku\events\EventStartGameResponse;
use app\components\sudoku\events\EventTopListResponse;

/**
 * Interface ClientApplicationInterface
 * @package app\components\sudoku
 */
interface ClientApplicationInterface
{
    /**
     * Event trigger when user wants to start game
     */
    const EVENT_START_GAME_REQUEST = 'startGameRequest';

    /**
     * Event trigger when the user wants to make a move
     */
    const EVENT_MOVE_REQUEST = 'eventMoveRequest';

    /**
     * Event trigger when the user wants to show top list
     */
    const EVENT_SHOW_TOP_LIST_REQUEST = 'eventShowTopListRequest';

    /**
     * @param EventStartGameResponse $event
     * @return void
     */
    public function startGame(EventStartGameResponse $event);

    /**
     * @param EventMoveResponse $event
     * @return void
     */
    public function move(EventMoveResponse $event);

    /**
     * @param EventTopListResponse $event
     * @return void
     */
    public function topList(EventTopListResponse $event);

}