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
        ClientApplicationInterface::EVENT_START_GAME_REQUEST,
        ClientApplicationInterface::EVENT_SHOW_TOP_LIST_REQUEST,
        ClientApplicationInterface::EVENT_MOVE_REQUEST,
    ];

    /**
     * @param string $msg
     * @param $clientId
     * @return Event|null
     * @throws \Exception
     */
    public static function createEvent($msg, $clientId): ?Event
    {
        $data = json_decode($msg, true);
        if (!isset($data['name'], $data['data'])) {
            return null;
        }

        if (!ArrayHelper::isIn($data['name'], static::$events)) {
            return null;
        }

        $event = new Event();
        $event->clientId = $clientId;
        $event->name = $data['name'];
        $event->eventData = $data['data'];
        return $event;
    }
}