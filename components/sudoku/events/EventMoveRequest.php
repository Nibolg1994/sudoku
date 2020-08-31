<?php

namespace app\components\sudoku\events;

/**
 * Class EventMoveRequest
 * @package app\components\sudoku\events
 */
class EventMoveRequest extends Event
{
    /**
     * @var int
     */
    public $cellId;

    /**
     * @var int
     */
    public $value;
}