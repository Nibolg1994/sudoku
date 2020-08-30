<?php
namespace app\components\sudoku\events;

use app\models\User;
use yii\base\Event;


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