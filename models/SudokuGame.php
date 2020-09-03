<?php

namespace app\models;

use yii\helpers\ArrayHelper;

/**
 * Class SudokuGame
 * @package app\models
 */
class SudokuGame
{
    /**
     * @var array
     */
    private static $values = [];

    /**
     * @var int
     */
    private static $countRows;

    /**
     * @var int
     */
    public const N = 3;

    /**
     * @var int
     */
    protected $countFreeCells;

    /**
     * @var array
     */
    protected $board;

    /**
     * @var SudokuSourceInterface
     */
    protected $source;

    /**
     * SudokuGame constructor.
     * @internal param $countFreeCells
     * @internal param $board
     * @param SudokuSourceInterface $source
     */
    public function __construct(SudokuSourceInterface $source)
    {
        $this->source = $source;
        $this->restart();
    }

    /**
     * Restart game
     */
    public function restart()
    {
        $countRows = static::getCountRows();
        $this->board = $this->source->create($countRows);
        $this->countFreeCells = $countRows * $countRows;
        foreach ($this->board as $row) {
            foreach ($row as $item) {
                if (!empty($item)) {
                    $this->countFreeCells--;
                }
            }
        }
    }

    /**
     * @return array
     */
    public function getBoard(): array
    {
        return $this->board;
    }

    /**
     * @return int
     */
    public function getCountFreeCells(): int
    {
        return $this->countFreeCells;
    }


    /**
     * @return bool
     */
    public function isEnd(): bool
    {
        if ($this->countFreeCells > 0) {
            return false;
        }

        for ($i = 0; $i < static::getCountRows(); $i ++) {
            $countCoincidence = count(array_intersect($this->board[$i], static::getValues()));
            if ($countCoincidence == static::getCountRows()) {
                return false;
            }
            $column = ArrayHelper::getColumn($this->board, $i);
            $countCoincidence = count(array_intersect($column, static::getValues()));
            if ($countCoincidence == static::getCountRows()) {
                return false;
            }

            $countCoincidence = count(array_intersect($this->getSquareItems($i), static::getValues()));
            if ($countCoincidence == static::getCountRows()) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param int $digit
     * @param int $idCell
     * @return bool
     */
    public function move(int $digit, int $idCell): bool
    {
        if (!in_array($digit, static::getValues())) {
            return false;
        }

        $countRows = static::getCountRows();

        $y = intdiv($idCell - 1, $countRows);
        $x = ($idCell - 1) % $countRows;

        if ($y >= $countRows || $y < 0) {
            return false;
        }

        if ($x >= $countRows || $x < 0) {
            return false;
        }

        if (empty($this->board[$y][$x])) {
            $this->countFreeCells--;
        }

        $this->board[$y][$x] = $digit;
        echo "move $x $y $idCell $digit\n";
        print_r($this->board[$y]);

        return true;
    }

    /**
     * @return array
     */
    public static function getValues(): array
    {
        if (empty(static::$values)) {
            for ($i = 1; $i <= static::getCountRows(); $i++) {
                static::$values[] = $i;
            }
        }

        return static::$values;
    }

    /**
     * @return int
     */
    public static function getCountRows(): int
    {
        if (empty(static::$countRows)) {
            static::$countRows = static::N * static::N;
        }
        return static::$countRows;
    }

    /**
     * @param $squareIndex
     * @return array
     */
    protected function getSquareItems($squareIndex): array
    {
        $rowIndex = intdiv($squareIndex, static::N);
        $columnIndex = ($squareIndex % static::N) * static::N;
        $items = [];

        for ($i = $rowIndex; $i < $rowIndex + static::N; $i++) {
            for ($j = $columnIndex; $j < $columnIndex + static::N; $j++) {
                $items[] = $this->board[$i][$j];
            }
        }

        return $items;
    }

}