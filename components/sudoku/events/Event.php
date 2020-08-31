<?php

namespace app\components\sudoku\events;

use ReflectionClass;
use ReflectionProperty;
use yii\base\Event as YiiEvent;

/**
 * Class Event
 * @package app\components\sudoku\events
 */
class Event extends YiiEvent
{
    /**
     * @throws \ReflectionException
     */
    public function __toString()
    {
        $reflect = new ReflectionClass($this);
        $props = $reflect->getProperties(ReflectionProperty::IS_PUBLIC);
        $data = [];
        foreach ($props as $prop) {
            if ($prop->getName() == 'sender') {
                continue;
            }

            $data[$prop->getName()] = $prop->getValue($this);
        }

        return json_encode($data);
    }
}