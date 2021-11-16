<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use BikeDriving\Exceptions\BoardException;
use BikeDriving\Models\Board;

class BoardTest extends TestCase
{
    public function testCannotCreateWithInvalidDimensions(): void
    {
        $this->expectException(BoardException::class);

        $board = new Board(-1, -1);
    }

    public function testReturnFalseOnInvalidPosition(): void
    {
        $board = new Board(7, 7);

        $this->assertFalse($board->validatePosition(-1, -1));
        $this->assertFalse($board->validatePosition(-1, 5));
        $this->assertFalse($board->validatePosition(5, -1));
        $this->assertFalse($board->validatePosition(5, 5));
    }

    public function testReturnTrueOnValidPosition(): void
    {
        $board = new Board(7, 7);

        $this->assertTrue($board->validatePosition(0, 0));
        $this->assertTrue($board->validatePosition(0, 4));
        $this->assertTrue($board->validatePosition(4, 0));
        $this->assertTrue($board->validatePosition(4, 4));
    }
}
