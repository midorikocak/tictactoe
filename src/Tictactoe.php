<?php
/**
 * Created by PhpStorm.
 * User: kocak
 * Date: 8.01.2017
 * Time: 11:30
 */

namespace MidoriKocak;

/**
 * Class Tictactoe
 * @package MidoriKocak
 */
class Tictactoe implements MoveInterface
{
    use BoardTrait;

    /**
     * Keeps the board's array. Should be NxN
     *
     * @var array
     */
    private $board;

    /**
     * Used by constructor for creating an empty array.
     *
     * @var int
     */
    private $boardSize = 3;

    /**
     * Board Size -1. Used for readability.
     *
     * @var int
     */
    private $maxIndex;

    /**
     * Class returns a message in each step. Winning is also detected by message.
     *
     * @var
     */
    public $message;

    /**
     * Keeps an array, containing x and y coordinates for next move, and th
     * e unit that now occupies it.
     * Example: [2, 0, 'O'] - upper right corner - O player
     *
     * @var array
     */
    private $nextMove = [];

    /**
     * Tictactoe constructor.
     * @param int $boardSize
     */
    public function __construct(int $boardSize)
    {
        $this->boardSize = $boardSize;
        $this->maxIndex = $boardSize - 1;
    }

    /**
     * Helper method for setting message.
     *
     * @param string $message
     */
    private function setMessage(string $message)
    {
        $this->message = $message;
    }

    /**
     * Creates an empty board depending on board Size.
     */
    public function emptyBoard()
    {
        $this->board = array_fill(0, $this->boardSize, array_fill(0, $this->boardSize, ''));
    }

    /**
     * Serves to inject the boardState to tictactoe object.
     *
     * @param array $board
     * @return bool
     */
    public function setBoard(array $board): bool
    {
        if (sizeof($board) == $this->boardSize && sizeof($board[0]) == $this->boardSize) {
            $this->board = $board;
            return true;
        }
        $this->setMessage("Board size is wrong.");
        return false;
    }

    /**
     * Prints array.
     * TODO: Unused method
     *
     * @return string
     */
    public function printTictactoe(): string
    {
        return $this->printBoard($this->board);
    }

    /**
     * Allows an user to play based on coordinates and playerUnit
     * TODO: unused
     *
     * @param int $y
     * @param int $x
     * @param string $playerUnit
     * @return bool
     */
    public function play(int $y, int $x, string $playerUnit = 'X') : bool
    {
        if ($this->checkIfEmpty($this->board, $y, $x)) {
            $this->board[$y][$x] = $playerUnit;
            return true;
        }
        $this->setMessage("Position is full.");
        return false;
    }

    /**
     * Defines next move if diagonals, columns and rows have only one count
     * of the player unit or the opponent.
     *
     * If block set to true, counts diagonals, rows and columns for the opponent.
     *
     * @param string $playerUnit
     * @param bool $block
     * @return bool
     */
    public function secondMove($playerUnit = 'X', $block = false)
    {
        $player = $playerUnit;

        if ($block) {
            if ($playerUnit == 'X') $playerUnit = 'O';
            if ($playerUnit == 'O') $playerUnit = 'X';
        }

        $rightDiagonalSum = 0;
        $leftDiagonalSum = 0;

        for ($i = 0; $i <= $this->maxIndex; $i++) {
            $column = array_column($this->board, $i);

            $rowSum = 0;

            foreach ($this->board[$i] as $item) {
                if ($item == $playerUnit) $rowSum++;

                if ($rowSum == 1 && empty($this->nextMove)) {
                    $coordinates = $this->findEmptyItemInRow($this->board, $i);
                    if (!empty($coordinates)) $this->nextMove = [$coordinates[1], $coordinates[0], $player];
                }
            }

            $columnSum = 0;

            foreach ($column as $item) {
                if ($item == $playerUnit) $columnSum++;

                if ($columnSum == 1 && empty($this->nextMove)) {
                    $coordinates = $this->findEmptyItemInColumn($this->board, $i);
                    if (!empty($coordinates)) $this->nextMove = [$coordinates[1], $coordinates[0], $player];
                }
            }

            if ($this->board[$i][$i] == $playerUnit) $rightDiagonalSum++;
            if ($this->board[$i][$this->maxIndex - $i] == $playerUnit) $leftDiagonalSum++;

            if ($rightDiagonalSum == 1 && empty($this->nextMove)) {
                $coordinates = $this->findEmptyItemInRightDiagonal($this->board);
                if (!empty($coordinates)) $this->nextMove = [$coordinates[1], $coordinates[0], $player];
            }

            if ($leftDiagonalSum == 1 && empty($this->nextMove)) {
                $coordinates = $this->findEmptyItemInleftDiagonal($this->board);
                if (!empty($coordinates)) $this->nextMove = [$coordinates[1], $coordinates[0], $player];
            }
        }

        if (empty($this->nextMove) && !$block) {
            $this->secondMove($playerUnit, true);
        }
        return false;
    }

    /**
     * Defines next move if diagonals, columns and rows have count two
     * of the player unit or the opponent.
     *
     * If block set to true, counts diagonals, rows and columns for the opponent.
     * Checks also if the player or the opponent won, sets the message.
     *
     * @param string $playerUnit
     * @param bool $block
     * @return bool
     */
    public function checkWinning($playerUnit = 'X', $block = false)
    {
        $player = $playerUnit;

        if ($block) {
            if ($playerUnit == 'X') {
                $playerUnit = 'O';
            } else {
                $playerUnit = 'X';
            }
        }

        $rightDiagonalSum = 0;
        $leftDiagonalSum = 0;

        for ($i = 0; $i <= $this->maxIndex; $i++) {

            if ($this->board[$i][$i] == $playerUnit) $rightDiagonalSum++;
            if ($this->board[$i][$this->maxIndex - $i] == $playerUnit) $leftDiagonalSum++;
            if ($rightDiagonalSum == $this->boardSize || $leftDiagonalSum == $this->boardSize) {
                $this->setMessage($playerUnit . " wins!");
                return true;
            }

            if ($rightDiagonalSum == $this->maxIndex) {
                $coordinates = $this->findEmptyItemInRightDiagonal($this->board);
                if (!empty($coordinates) && empty($this->nextMove)) $this->nextMove = [$coordinates[1], $coordinates[0], $player];
            }

            if ($leftDiagonalSum == $this->maxIndex) {
                $coordinates = $this->findEmptyItemInleftDiagonal($this->board);
                if (!empty($coordinates) && empty($this->nextMove)){
                    $this->nextMove = [$coordinates[1], $coordinates[0], $player];
                }
            }

            $column = array_column($this->board, $i);
            $columnSum = 0;

            foreach ($column as $item) {
                if ($item == $playerUnit) $columnSum++;
                if ($columnSum == $this->maxIndex) {
                    $coordinates = $this->findEmptyItemInColumn($this->board, $i);
                    if (!empty($coordinates) && empty($this->nextMove)) $this->nextMove = [$coordinates[1], $coordinates[0], $player];
                }

                if ($columnSum == $this->boardSize) {
                    $this->setMessage($playerUnit . " wins!");
                    return true;
                }
            }
            $rowSum = 0;

            foreach ($this->board[$i] as $item) {
                if ($item == $playerUnit) $rowSum++;

                if ($rowSum == $this->maxIndex) {
                    $coordinates = $this->findEmptyItemInRow($this->board, $i);
                    if (!empty($coordinates) && empty($this->nextMove)) $this->nextMove = [$coordinates[1], $coordinates[0], $player];
                }

                if ($rowSum == $this->boardSize) {
                    $this->setMessage($playerUnit . " wins!");
                    return true;
                }
            }
        }

        if (empty($this->nextMove) && !$block) {
            $this->checkWinning($playerUnit, true);
            if (empty($this->nextMove)) {
                $this->secondMove($playerUnit);
            }
        }

        if($this->message == "") $this->setMessage($player . " played.");
        return false;
    }

    /**
     * Get's the injected boardState, checks by counting diagonals, columns and rows.
     * If won, returns an empty array.
     *
     * @param array $boardState
     * @param string $playerUnit
     * @return array
     */
    public function makeMove(array $boardState, string $playerUnit = 'X'): array
    {
        $this->setBoard($boardState);
        // Check if opponent won.
        $result = false;
        if ($playerUnit == 'X') {
            $result = $this->checkWinning('O');
        } else {
            $result = $this->checkWinning('X');
        }

        if(!$result){
            $this->nextMove = [];
            $this->message = "";
            $result = $this->checkWinning($playerUnit);
            if(!$result && !empty($this->nextMove)){
                $this->board[$this->nextMove[1]][$this->nextMove[0]] = $this->nextMove[2];
                $result = $this->checkWinning($playerUnit);
            }
            if(empty($this->nextMove)){
                $this->message = "No one wins.";
            }
            return $this->nextMove;
        }
        return [];
    }
}