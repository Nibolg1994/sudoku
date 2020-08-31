<?php

namespace app\components\sudoku\events;

use app\models\User;

/**
 * Class EventTopListResponse
 * @package app\components\sudoku\events
 */
class EventTopListResponse extends Event
{
    /**
     * @var User
     */
    public $user;

    /**
     * @var array
     */
    public $topList;

}