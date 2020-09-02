<?php


namespace app\components\sudoku\events;

/**
 * Class ErrorEvent
 * @package app\components\sudoku\events
 */
class ErrorEvent extends BaseEvent
{
    /**
     * @var string
     */
    public $message;

    /**
     * @return string
     */
    public function __toString()
    {
        return json_encode([
            'event' => $this->name,
            'error' => $this->message,
            'data' => []
        ]);
    }
}