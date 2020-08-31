<?php

namespace app\components\sudoku\events;


/**
 * Class EventStartGameResponse
 * @package app\components\sudoku\events
 */
class EventStartGameResponse extends Event
{
    /**
     * @var array $board
     */
    public $board;
}