<?php

namespace app\components\sudoku\events;

use yii\base\Event;


/**
 * Class EventMoveResponse
 * @package app\components\sudoku\events
 */
class EventMoveResponse extends Event
{
    /**
     * @var int
     */
    public $idCell;

    /**
     * @var int
     */
    public $value;

    /**
     * @var [Users]
     */
    public $users;
}