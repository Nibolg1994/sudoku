<?php

namespace app\models;

/**
 * Interface SudokuStorageInterface
 * @package app\models
 */
interface SudokuStorageInterface
{
    /**
     * @param User $user
     * @return bool
     */
    public function save(User $user): bool;

    /**
     * @return array
     */
    public function getTopList(): array;
}