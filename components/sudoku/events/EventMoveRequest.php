<?php

namespace app\components\sudoku\events;

/**
 * Class EventMoveRequest
 * @package app\components\sudoku\events
 */
class EventMoveRequest extends EventRequest
{
    /**
     * @var int
     */
    public $idCell;

    /**
     * @var int
     */
    public $value;
}