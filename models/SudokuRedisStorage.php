<?php

namespace app\models;

use \Yii;

/**
 * Class SudokuRedisStorage
 * @package app\models
 */
class SudokuRedisStorage implements SudokuStorageInterface
{
    /**
     * @var string
     */
    const REDIS_KEY = "users";

    /**
     * @var bool
     */
    private $error = false;

    /**
     * @param User $user
     * @return bool
     */
    public function save(User $user): bool
    {
        $this->call('hincrby', [static::REDIS_KEY, $user->name, 1]);
        return $this->hasError();
    }

    /**
     * @return bool
     */
    public function hasError(): bool
    {
        return $this->error;
    }

    /**
     * @param $name
     * @param array $params
     * @return array|bool|string|null
     */
    protected function call($name, $params = [])
    {
        $this->error = false;
        try {
            $redis = Yii::$app->get('redis');
            return $redis->executeCommand($name, $params);
        } catch (\Exception $e) {
            Yii::error($e->getMessage() . PHP_EOL . $e->getTraceAsString());
            $this->error = true;
            return null;
        }
    }

    /**
     * @return array
     */
    public function getTopList(): array
    {
        $redisValues = $this->call('HGETALL', [static::REDIS_KEY]);

        if (empty($redisValues)) {
            return [];
        }

        $values = [];
        for ($i = 0; $i < count($redisValues); $i += 2) {
            $values[$redisValues[$i]] = $redisValues[$i + 1];
        }

        rsort($values);

        return $values;
    }
}