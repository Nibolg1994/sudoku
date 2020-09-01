<?php

namespace app\components\sudoku\events;

use app\models\User;
use ReflectionClass;
use ReflectionProperty;
use yii\base\Event as YiiEvent;
use yii\helpers\ArrayHelper;

/**
 * Class Event
 * @package app\components\sudoku\events
 */
class Event extends YiiEvent
{
    /**
     * @var User
     */
    public $user;

    /**
     * @throws \ReflectionException
     */
    public function __toString()
    {
        $reflect = new ReflectionClass($this);
        $props = $reflect->getProperties(ReflectionProperty::IS_PUBLIC);
        $data = [];
        foreach ($props as $prop) {
            if (ArrayHelper::isIn($prop->getName(), ['sender', 'user'])) {
                continue;
            }
            $data[$prop->getName()] = $prop->getValue($this);
        }

        return json_encode($data);
    }

    /**
     * @return bool
     */
    public function validate(): bool
    {
        return $this->user && $this->user->validate();
    }
}