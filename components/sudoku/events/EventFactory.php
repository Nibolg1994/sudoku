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
        $data = json_decode($msg);
        if (!isset($data['name'], $data['data'])) {
            return null;
        }

        $action = ArrayHelper::keyExists($data['name'], static::$events);
        if (!$action) {
            return null;
        }

        $event = new Event();
        $event->clientId = $clientId;
        $event->name = $data['name'];
        $event->data = $data['data'];
        return $event;
    }
}