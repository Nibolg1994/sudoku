<?php

namespace app\components\sudoku\clients;

use app\components\sudoku\events\BaseEvent;

/**
 * Interface ClientApplicationInterface
 * @package app\components\sudoku
 */
interface ClientApplicationInterface
{
    /**
     * Event trigger when user wants to start game
     */
    public const EVENT_START_GAME_REQUEST = 'startGameRequest';

    /**
     * Event trigger when the user wants to make a move
     */
    public const EVENT_MOVE_REQUEST = 'eventMoveRequest';

    /**
     * Event trigger when the user wants to show top list
     */
    public const EVENT_SHOW_TOP_LIST_REQUEST = 'eventShowTopListRequest';

    /**
     * Event trigger when the user disconnected
     */
    public const EVENT_DISCONNECT = 'eventDisconnect';

    /**
     * @param BaseEvent $event
     * @return void
     */
    public function sendEvent(BaseEvent $event);

    /**
     * @param BaseEvent $event
     * @return void
     */
    public function sendBroadcastEvent(BaseEvent $event);
}