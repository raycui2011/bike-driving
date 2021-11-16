<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use BikeDriving\Exceptions\BikeException;
use BikeDriving\Models\Board;
use BikeDriving\Models\Robot;

class BikeTest extends TestCase
{
    protected $bike;

    protected function setUp(): void
    {
        $board = new Board(5, 5);
        $this->bike = new Bike($board);
    }

    public function testPositionIsNullOnInit()
    {
        $this->assertNull($this->bike->getPosition());
    }

    public function testCannotSetInvalidSouthWestPosition()
    {
        $this->expectException(BikeException::class);

        $this->bike->setPosition(-1, -1);
    }

    public function testCannotSetInvalidNorthWestPosition()
    {
        $this->expectException(BikeException::class);

        $this->bike->setPosition(-1, 5);
    }

    public function testCannotSetInvalidNorthEastPosition()
    {
        $this->expectException(BikeException::class);

        $this->bike->setPosition(5, 5);
    }

    public function testCannotSetInvalidSouthEastPosition()
    {
        $this->expectException(BikeException::class);

        $this->bike->setPosition(5, -1);
    }

    public function testCanSetValidPosition()
    {
        $this->bike->setPosition(3, 3);
        $position = $this->bike->getPosition();

        $this->assertEquals($position['x'], 3);
        $this->assertEquals($position['y'], 3);
    }

    public function testDirectionIsNullOnInit()
    {
        $this->assertNull($this->bike->getDirection());
    }

    public function testFailIfSetInvalidDirection()
    {
        $this->expectException(BikeException::class);

        $this->bike->setDirection('ASDF');
    }

    public function testCanSetNorthDirection()
    {
        $this->bike->setDirection('NORTH');
        $direction = $this->bike->getDirection();

        $this->assertEquals($direction['name'], 'north');
        $this->assertEquals($direction['x'], 0);
        $this->assertEquals($direction['y'], 1);
    }

    public function testCanSetEastDirection()
    {
        $this->bike->setDirection('EAST');
        $direction = $this->bike->getDirection();

        $this->assertEquals($direction['name'], 'east');
        $this->assertEquals($direction['x'], 1);
        $this->assertEquals($direction['y'], 0);
    }

    public function testCanSetSouthDirection()
    {
        $this->bike->setDirection('SOUTH');
        $direction = $this->bike->getDirection();

        $this->assertEquals($direction['name'], 'south');
        $this->assertEquals($direction['x'], 0);
        $this->assertEquals($direction['y'], -1);
    }

    public function testCanSetWestDirection()
    {
        $this->bike->setDirection('WEST');
        $direction = $this->bike->getDirection();

        $this->assertEquals($direction['name'], 'west');
        $this->assertEquals($direction['x'], -1);
        $this->assertEquals($direction['y'], 0);
    }

    public function testCanPlaceWithValidPositionAndDirection()
    {
        $this->bike->place(1, 1, 'WEST');
        $direction = $this->bike->getDirection();
        $position = $this->bike->getPosition();

        $this->assertEquals($direction['name'], 'west');
        $this->assertEquals($position['x'], 1);
        $this->assertEquals($position['y'], 1);
    }

    public function testCannotPlaceWithInvalidPosition()
    {
        $this->expectException(BikeException::class);

        $this->bike->place(-1, -1, 'WEST');
    }

    public function testCannotPlaceWithInvalidDirection()
    {
        $this->expectException(BikeException::class);

        $this->bike->place(1, 1, 'ASDF');
    }

    public function testCannotMoveIfUnplaced()
    {
        $this->expectException(BikeException::class);

        $this->bike->move();
    }

    public function testCannotMoveToInvalidPosition()
    {
        $this->bike->place(0, 0, 'SOUTH');

        $this->expectException(BikeException::class);

        $this->bike->move();
    }

    public function testCanMoveNorthToValidPosition()
    {
        $this->bike->place(1, 1, 'NORTH');
        $this->bike->move();

        $position = $this->bike->getPosition();

        $this->assertEquals($position['x'], 1);
        $this->assertEquals($position['y'], 2);
    }

    public function testCanMoveEastToValidPosition()
    {
        $this->bike->place(1, 1, 'EAST');
        $this->bike->move();

        $position = $this->bike->getPosition();

        $this->assertEquals($position['x'], 2);
        $this->assertEquals($position['y'], 1);
    }

    public function testCanMoveSouthToValidPosition()
    {
        $this->bike->place(1, 1, 'SOUTH');
        $this->bike->move();

        $position = $this->bike->getPosition();

        $this->assertEquals($position['x'], 1);
        $this->assertEquals($position['y'], 0);
    }

    public function testCanMoveWestToValidPosition()
    {
        $this->bike->place(1, 1, 'WEST');
        $this->bike->move();

        $position = $this->bike->getPosition();

        $this->assertEquals($position['x'], 0);
        $this->assertEquals($position['y'], 1);
    }

    public function testCanMoveMultipleSteps()
    {
        $this->bike->place(0, 0, 'NORTH');
        $this->bike->move(4);

        $position = $this->bike->getPosition();

        $this->assertEquals($position['x'], 0);
        $this->assertEquals($position['y'], 4);
    }

    public function testCanMoveZeroSteps()
    {
        $this->bike->place(0, 0, 'NORTH');
        $this->bike->move(0);

        $position = $this->bike->getPosition();

        $this->assertEquals($position['x'], 0);
        $this->assertEquals($position['y'], 0);
    }

    public function testCannotRotateLeftIfUnplaced()
    {
        $this->expectException(BikeException::class);

        $this->bike->rotateLeft();
    }

    public function testCannotRotateRightIfUnplaced()
    {
        $this->expectException(BikeException::class);

        $this->bike->rotateRight();
    }

    public function testCanRotateLeft()
    {
        $this->bike->place(1, 1, 'SOUTH');

        $this->bike->rotateLeft();
        $this->assertEquals($this->bike->getDirectionName(), 'east');

        $this->bike->rotateLeft();
        $this->assertEquals($this->bike->getDirectionName(), 'north');

        $this->bike->rotateLeft();
        $this->assertEquals($this->bike->getDirectionName(), 'west');

        $this->bike->rotateLeft();
        $this->assertEquals($this->bike->getDirectionName(), 'south');
    }

    public function testCanRotateRight()
    {
        $this->bike->place(1, 1, 'SOUTH');

        $this->bike->rotateRight();
        $this->assertEquals($this->bike->getDirectionName(), 'west');

        $this->bike->rotateRight();
        $this->assertEquals($this->bike->getDirectionName(), 'north');

        $this->bike->rotateRight();
        $this->assertEquals($this->bike->getDirectionName(), 'east');

        $this->bike->rotateRight();
        $this->assertEquals($this->bike->getDirectionName(), 'south');
    }

    public function testCannotReportIfUnplaced()
    {
        $this->expectException(BikeException::class);

        $this->bike->report();
    }

    public function testCanReport()
    {
        $this->bike->place(1, 1, 'SOUTH');

        $report = $this->bike->report();

        $this->assertEquals($report, '1,1,SOUTH');
    }
}
