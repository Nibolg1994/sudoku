<?php
namespace app\components\sudoku\events;

use app\models\User;


/**
 * Class SudokuEvent
 * @package app\components\sudoku\events
 */
class EventRequest extends Event
{
    /**
     * @var User
     */
    public $user;
}