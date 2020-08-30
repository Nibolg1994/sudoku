<?php

namespace app\models;

/**
 * Class SudokuInitializer
 * @package app\models
 */
class SudokuApiSource implements SudokuSourceInterface
{
    /**
     * @var int
     */
    const LEVEL_EASY = 1;

    /**
     * @var int
     */
    const LEVEL_MEDIUM = 2;

    /**
     * @var int
     */
    const LEVEL_HARD = 3;

    /**
     * @var string
     */
    private static $endPoint = 'http://www.cs.utep.edu/cheon/ws/sudoku/new';

    /**
     * Create sudoku board
     *
     * @param int $n
     * @return array
     * @throws \Exception
     */
    public function create(int $n): array
    {
        $response = file_get_contents(
            static::$endPoint . "?size=" . $n . "&level=" . static::LEVEL_MEDIUM
        );
        $response = json_decode($response, true);

        if (empty($response['response']) || empty($response['squares'])) {
            throw new \Exception("Bad api response");
        }

        $board = static::fillEmptyBoard($n);
        foreach ($response['squares'] as $item) {
            $board[$item['y']][$item['x']] = $item['value'];
        }

        return $board;
    }

    /**
     * @param $n
     * @return array
     */
    protected static function fillEmptyBoard(int $n): array
    {
        $board = [];
        for ($i = 0; $i < $n; $i++) {
            for ($j = 0; $j < $n; $j++) {
                $board[$i][$j] = null;
            }
        }
        return $board;
    }
}