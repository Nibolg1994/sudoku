<?php

namespace app\components\sudoku;


use app\components\sudoku\events\Event;

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
     * Server trigger event when user disconnected from game
     */
    const EVENT_FREE_CELLS = 'eventFreeCells';

    /**
     * Event trigger when server send error
     */
    const EVENT_ERROR = 'eventError';

    /**
     * Event trigger when game over
     */
    const EVENT_FINISH = 'eventFinish';

    /**
     * @param Event $event
     * @return mixed
     */
    public function start(Event $event);

    /**
     * @param Event $event
     * @return mixed
     */
    public function move(Event $event);

    /**
     * @param Event $event
     * @return mixed
     */
    public function topList(Event $event);

    /**
     * @param Event $event
     * @return mixed
     */
    public function disconnect(Event $event);
}