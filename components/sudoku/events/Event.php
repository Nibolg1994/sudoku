<?php

namespace app\components\sudoku\events;


/**
 * Class Event
 * @package app\components\sudoku\events
 */
class Event extends BaseEvent
{
    /**
     * @var array
     */
   public $eventData = [];

    /**
     * @return string
     */
    public function __toString()
    {
        return json_encode([
            'event' => $this->name,
            'data' => $this->eventData
        ]);
    }
}