<?php

namespace app\components\sudoku\events;

use app\models\User;


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