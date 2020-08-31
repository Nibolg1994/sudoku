<?php

use app\models\User;

/**
 * Class UserRepositry
 */
class UserRepository
{
    /**
     * @var array
     */
    private static $users = [];

    /**
     * @param $userId
     * @return mixed|null
     */
    public static function get($userId)
    {
        if (static::exists($userId)) {
            return static::$users[$userId];
        }

        return null;
    }

    /**
     * @param $userId
     * @return bool
     */
    public static function exists($userId)
    {
        if (isset(static::$users[$userId])) {
            return true;
        }

        return false;
    }

    /**
     * @param User $user
     */
    public static function add(User $user)
    {
        static::$users[$user->id] = $user;
    }

    /**
     * @param $userId
     * @return bool
     */
    public static function remove($userId): bool
    {
        if (isset(static::$users[$userId])) {
            unset(static::$users[$userId]);
            return true;
        }

        return false;
    }

    /**
     * @param $name
     * @return bool
     */
    public static function hasName($name): bool
    {
        foreach (static::$users as $user) {
            if ($user->name == $name) {
                return true;
            }
        }
        return false;
    }
}