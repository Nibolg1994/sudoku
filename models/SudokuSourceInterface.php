<?php

namespace app\models;


/**
 * Interface SudokuSourceInterface
 * @package app\models
 */
interface SudokuSourceInterface
{
    /**
     * Create sudoku board
     *
     * @param int $n
     * @return array
     */
   public function create(int $n): array;
}