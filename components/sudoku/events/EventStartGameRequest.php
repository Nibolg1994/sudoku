<?php

namespace app\components\sudoku\events;

use UserRepository;


/**
 * Class EventStartGameRequest
 * @package app\components\sudoku\events
 */
class EventStartGameRequest extends Event
{
    /**
     * @return bool
     */
    public function validate(): bool
    {
       if (!parent::validate()) {
           return false;
       }

       if (!UserRepository::exists($this->user->id)
           && UserRepository::hasName($this->user->name)
       ) {
           return false;
       }

       return true;
   }
}