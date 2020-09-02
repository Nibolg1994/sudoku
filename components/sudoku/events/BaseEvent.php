<?php


namespace app\components\sudoku\events;

use yii\base\Event as YiiEvent;

/**
 * Class BaseEvent
 * @package app\components\sudoku\events
 */
abstract class BaseEvent extends YiiEvent
{
    /**
     * @var
     */
    public $clientId;

    /**
     * @return string
     */
    abstract function __toString();
}