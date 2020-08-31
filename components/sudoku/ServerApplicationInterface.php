<?php

namespace app\components\sudoku;


use app\components\sudoku\events\EventMoveRequest;
use app\components\sudoku\events\EventRequest;

/**
 * Interface ServerApplicationInterface
 * @package app\components\sudoku
 */
interface ServerApplicationInterface
{
    /**
     * Event trigger when server allows the user to start game
     */
    const EVENT_START_GAME_ACCEPT = 'startGameAccept';

    /**
     * Event trigger when server allows the user to make a move
     */
    const EVENT_MOVE_RESPONSE = 'eventMoveRepose';

    /**
     * Event trigger when server sends top list to the user
     */
    const EVENT_SHOW_TOP_LIST_RESPONSE = 'eventShowTopListResponse';

    /**
     * @param EventRequest $event
     * @return mixed
     */
    public function startGame(EventRequest $event);

    /**
     * @param EventMoveRequest $event
     * @return mixed
     */
    public function move(EventMoveRequest $event);

    /**
     * @param EventRequest $event
     * @return mixed
     */
    public function topList(EventRequest $event);
}