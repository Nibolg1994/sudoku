<?php

namespace app\components\sudoku\events;


use app\components\sudoku\clients\ClientApplicationInterface;
use app\models\User;
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
        ClientApplicationInterface::EVENT_START_GAME_REQUEST => 'createStartGameEvent',
        ClientApplicationInterface::EVENT_SHOW_TOP_LIST_REQUEST => 'createBaseEvent',
        ClientApplicationInterface::EVENT_MOVE_REQUEST => 'createEventMoveRequest',
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
        if (empty($data) || empty($data['name'])) {
            return null;
        }

        $action = ArrayHelper::getValue(static::$events, $data['name']);
        if (!$action) {
            return null;
        }

        $event = call_user_func([static::class, $action], $data, $clientId);

        if (!$event->user) {
            $event->user = \UserRepository::get($clientId);
        }

        return $event;
    }


    /**
     * @param array $data
     * @return EventMoveRequest|null
     */
    protected static function createEventMoveRequest(array $data, $clientId): ?EventMoveRequest
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
    protected static function createBaseEvent(array $data, $clientId): ?Event
    {
        if (!isset($data['name'])) {
            return null;
        }

        $event = new Event();
        $event->name = $data['name'];
        return $event;
    }


    /**
     * @param array $data
     * @param $clientId
     * @return Event|null
     */
    protected static function createStartGameEvent(array $data, $clientId): ?Event
    {
        $event = static::createBaseEvent($data, $clientId);

        if (!isset($data['user'], $event)) {
            return null;
        }

        $user = new User();
        $user->id = $clientId;
        $user->name = $data['user'];
        $event->user = $user;


        return $event;
    }
}