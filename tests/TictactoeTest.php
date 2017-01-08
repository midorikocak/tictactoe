<?php

use MidoriKocak\Tictactoe;


class TictactoeTest extends \PHPUnit_Framework_TestCase
{

    private $emptyBoard = [['', '', ''], ['', '', ''], ['', '', '']];

    private $rightDiagonalWinningBoard = [['X', '', ''], ['', 'X', ''], ['', '', 'X']];
    private $leftDiagonalWinningBoard= [['', '', 'X'], ['', 'X', ''], ['X', '', '']];
    private $columnWinningBoard = [['X', '', ''], ['X', '', ''], ['X', '', '']];
    private $rowWinningBoard = [['X', 'X', 'X'], ['', '', ''], ['', '', '']];
    private $cornerBoard = [['X', '', ''], ['', '', ''], ['', '', '']];
    private $leftDiagonalEmpty= [['', '', 'X'], ['', 'X', ''], ['', '', '']];
    private $boardSize = 3;
    private $printedBoard = ",,\n,,\n,,\n";
    private $tictactoe;

    public function setup()
    {
        $this->tictactoe = new Tictactoe($this->boardSize);
        $this->tictactoe->emptyBoard();
    }

    public function testPrint()
    {
        $this->assertEquals($this->tictactoe->printTictactoe(), $this->printedBoard);
    }

    public function testPlay()
    {
        $this->assertTrue($this->tictactoe->play(0, 0, 'X'));
        $this->tictactoe->setBoard($this->cornerBoard);
        $this->assertFalse($this->tictactoe->play(0, 0, 'X'));
    }

    public function testWinning()
    {
        $this->tictactoe->setBoard($this->rightDiagonalWinningBoard);
        $this->assertTrue($this->tictactoe->checkWinning());
        $this->tictactoe->setBoard($this->leftDiagonalWinningBoard);
        $this->assertTrue($this->tictactoe->checkWinning());
        $this->tictactoe->setBoard($this->columnWinningBoard);
        $this->assertTrue($this->tictactoe->checkWinning());
        $this->tictactoe->setBoard($this->rowWinningBoard);
        $this->assertTrue($this->tictactoe->checkWinning());
    }

    public function testFindEmptyItems(){
        $this->tictactoe->setBoard($this->rightDiagonalWinningBoard);
        $this->assertEquals($this->tictactoe->findEmptyItemInColumn($this->rightDiagonalWinningBoard, 0), [1,0]);
        $this->assertEquals($this->tictactoe->findEmptyItemInRow($this->rightDiagonalWinningBoard, 0), [0,1]);
        $this->assertEquals($this->tictactoe->findEmptyItemInRightDiagonal($this->rightDiagonalWinningBoard),[]);
        $this->assertEquals($this->tictactoe->findEmptyItemInLeftDiagonal($this->leftDiagonalEmpty),[2,0]);
    }
}