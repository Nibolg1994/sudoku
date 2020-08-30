<?php

namespace app\models;

/**
 * Class CompetitiveSudoku
 * @package app\models
 */
/**
 * Class CompetitiveSudoku
 * @package app\models
 */
class CompetitiveSudoku
{
    /**
     * @var SudokuGame
     */
    private $game;

    /**
     * @var SudokuSourceInterface
     */
    private $source;

    /**
     * @var SudokuStorageInterface
     */
    private $storage;

    /**
     * @var [User]
     */
    private $users;

    /**
     * @var array
     */
    private $moves;

    /**
     * @var User
     */
    private $lastUser;

    /**
     * CompetitiveSudoku constructor.
     * @param SudokuSourceInterface $source
     * @param SudokuStorageInterface $storage
     * @internal param User|null $user
     */
    public function __construct(
        SudokuSourceInterface $source,
        SudokuStorageInterface $storage
    )
    {
        $this->source = $source;
        $this->storage = $storage;
        $this->restart();
    }

    /**
     * @return SudokuStorageInterface
     */
    public function getStorage(): SudokuStorageInterface
    {
        return $this->storage;
    }

    /**
     * @param User $user
     */
    public function connect(User $user)
    {
        if (empty($this->users[$user->id])) {
            $this->users[$user->id] = $user;
        }
    }

    /**
     * @param User $user
     * @param int $cellId
     * @param int $value
     * @return bool
     */
    public function move(User $user, int $cellId, int $value)
    {
        $this->connect($user);

        if (!empty($this->moves[$cellId])
            && $user->id != $this->moves[$cellId]
        ) {
            return false;
        }

        if (!$this->game->placeDigit($value, $cellId)) {
            return false;
        }

        $this->lastUser = $user;
        $this->moves[$cellId] = $user->id;

        return true;
    }

    /**
     * @return bool
     */
    public function isEnd(): bool
    {
        return $this->game->isEnd();
    }

    /**
     * @return bool
     */
    public function finish()
    {
        if (!$this->isEnd()) {
            return false;
        }

        $this->storage->save($this->lastUser);

        return true;
    }

    /**
     * @return mixed
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @return array
     */
    public function getBoard()
    {
        return $this->game->getBoard();
    }

    /**
     * Restart game
     */
    public function restart()
    {
        $this->game = new SudokuGame($this->source);
        $this->users = [];
        $this->moves = [];
    }
}