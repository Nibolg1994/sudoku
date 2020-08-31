<?php

namespace app\components\sudoku\events;


use app\components\sudoku\clients\ClientApplicationInterface;
use yii\helpers\ArrayHelper;

/**
 * Class EventFactory
 * @package app\components\sudoku\events
 */
class EventFactory
{
    /**
     * @var array
     */
    protected static $events = [
        ClientApplicationInterface::EVENT_START_GAME_REQUEST => 'createBaseEvent',
        ClientApplicationInterface::EVENT_SHOW_TOP_LIST_REQUEST => 'createBaseEvent',
        ClientApplicationInterface::EVENT_MOVE_REQUEST => 'createEventMoveRequest',
    ];

    /**
     * @param string $msg
     * @return Event|null
     */
    public static function createEvent($msg): ?Event
    {
        $data = json_decode($msg);
        if (empty($data) || empty($data['name'])) {
            return null;
        }

        $action = ArrayHelper::getValue(static::$events, $data['name']);
        if (!$action) {
            return null;
        }

        return call_user_func([static::class, $action], $data);
    }

    /**
     * @param array $data
     * @return EventMoveRequest|null
     */
    protected static function createEventMoveRequest(array $data): ?EventMoveRequest
    {
        if (!isset(
            $data['cellId'],
            $data['value'],
            $data['name'])
        ) {
            return null;
        }

        $event = new EventMoveRequest();
        $event->value = $data['value'];
        $event->cellId = $data['cellId'];
        $event->name = $data['name'];
        return $event;
    }


    /**
     * @param array $data
     * @return Event|null
     */
    protected static function createBaseEvent(array $data): ?Event
    {
        if (!isset($data['name'])) {
            return null;
        }

        $event = new Event();
        $event->name = $data['name'];
        return $event;
    }
}