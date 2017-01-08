<?php

namespace MidoriKocak;

/**
 * Mostly Helper methods for different NxN Boards
 *
 * Class BoardTrait
 * @package MidoriKocak
 */
trait BoardTrait
{
    /**
     *
     * Prints array used just for testing and cli
     * TODO: Delete
     *
     * @param array|null $array
     * @return string
     */
    function printBoard(array $array = null) : string
    {
        if($array == null) $array = $this->board;
        $result = "";
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $result .= $this->printBoard($value);
                $result .= "\n";
            } else {
                $result .= $value;
                if ($key < $this->maxIndex) $result .= ",";
            }
        }
        return $result;
    }

    /**
     * Gives neighboor keys of a given coordinate in an NxN array
     *
     * @param int $size
     * @param int $y
     * @param int $x
     * @return array
     */
    function findNeighboorKeys(int $size, int $y, int $x):array
    {
        $result = [];
        if ($y - 1 >= 0) {
            array_push($result, [$y - 1, $x]);
            if ($x == $y) array_push($result, [$y - 1, $x - 1]);
        }

        if ($y + 1 < $size) {
            array_push($result, [$y + 1, $x]);
            if ($x == $y) array_push($result, [$y + 1, $x + 1]);
        }
        if ($x - 1 >= 0) array_push($result, [$y, $x - 1]);
        if ($x + 1 < $size) array_push($result, [$y, $x + 1]);
        return $result;
    }

    /**
     * Checks if the coordinate is empty
     *
     * @param array $board
     * @param int $y
     * @param int $x
     * @return bool
     */
    function checkIfEmpty(array $board, int $y, int $x): bool
    {
        if ($board[$y][$x] == '') {
            return true;
        }
        return false;
    }

    /**
     * Check if array is already filled with data
     *
     * @param array $board
     * @return bool
     */
    function checkIfArrayFull(array $board) : bool
    {
        foreach ($board as $line) {
            foreach ($line as $value) {
                if ($value == '') return false;
            }
        }
        return true;
    }

    /**
     * Retrieves first empty coordinates in a given column.
     *
     * @param array $board
     * @param int $x
     * @return array
     */
    function findEmptyItemInColumn(array $board, int $x) : array
    {
        $column = array_column($board, $x);
        for ($i = 0; $i < sizeof($column); $i++)
        {
            if ($column[$i] == '') return [$i, $x];
        }
        return [];
    }

    /**
     * Retrieves first empty coordinates in a given Row.
     *
     * @param array $board
     * @param int $y
     * @return array
     */
    function findEmptyItemInRow(array $board, int $y)
    {
        for ($i = 0; $i < sizeof($board); $i++)
        {
            if ($board[$y][$i] == '') return [$y, $i];
        }
        return [];
    }

    /**
     * Retrieves first empty coordinates in left diagonal of an NxN array.
     *
     * @param array $board
     * @return array
     */
    function findEmptyItemInLeftDiagonal(array $board)
    {
        for ($y = 0; $y < sizeof($board); $y++)
        {
            $x = sizeof($board) - $y - 1;
            if ($board[$y][$x] == '') return [$y, $x];
        }
        return [];
    }

    /**
     * Retrieves first empty coordinates in right diagonal of an NxN array.
     *
     * @param array $board
     * @return array
     */
    function findEmptyItemInRightDiagonal(array $board)
    {
        for ($i = 0; $i <= sizeof($board)-1; $i++)
        {
            if ($board[$i][$i] == '') return [$i, $i];
        }
        return [];
    }

    /**
     * Gives a random empty position in an Array.
     *
     * TODO: Unused
     *
     * @param array $board
     * @return array|bool
     */
    function getRandomEmptyPosition(array $board)
    {
        if ($this->checkIfArrayFull($board)) {
            return false;
        }
        $maxIndex = sizeof($board) - 1;
        $x = rand(0, $maxIndex);
        $y = rand(0, $maxIndex);
        while (!$this->checkIfEmpty($board, $y, $x)) {
            $x = rand(0, $maxIndex);
            $y = rand(0, $maxIndex);
        }
        return [$y, $x];
    }
}