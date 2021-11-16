<?php declare(strict_types=1);

namespace BikeDriving\Models;

use BikeDriving\Exceptions\BikeException;
use BikeDriving\Models\Board;

/**
 *  Class for controlling the Bike's position and direction.
 */
class Bike
{
    /**
     * Board defines the Bike's (x, y) boundaries.
     *
     * @var Board
     */
    private $board;

    /**
     * Bike's current position as (x, y) array.
     * Null if the bike is unplaced.
     *
     * Format: ['x' => 3, 'y' => 3]
     *
     * @var array|null
     */
    private $position;

    /**
     * Current index of the direction that the bike is facing.
     * Null if the bike is unplaced.
     *
     * @var int|null
     */
    private $direction;

    /**
     * List of valid directions the bike can be facing.
     *
     * In this list structure we have an (x, y) vector
     * so we can tell the bike which direction to move in,
     * as well as a name so we can tell the humans.
     *
     * Instead of keying this array by the direction name,
     * index keys were chosen instead so we can easily cycle
     * through clockwise or anticlockwise by adding or subtracting
     * from the current index.
     */
    const DIRECTIONS = [
        ['name' => 'north', 'x' => 0, 'y' => 1],
        ['name' => 'east', 'x' => 1, 'y' => 0],
        ['name' => 'south', 'x' => 0, 'y' => -1],
        ['name' => 'west', 'x' => -1, 'y' => 0],
    ];

    /**
     * Enumerate our direction indexes so we can grab them cleanly.
     */
    const NORTH = 0;
    const EAST = 1;
    const SOUTH = 2;
    const WEST = 3;

    public function __construct(Board $board)
    {
        $this->board = $board;
        $this->position = null;
        $this->direction = null;
    }

    /**
     * Get Bike position as (x, y) array.
     *
     * Format: ['x' => 3, 'y' => 3]
     *
     * @return array|null
     */
    public function getPosition(): ?array
    {
        return $this->position;
    }

    /**
     * Set Bike position from an (x, y) "tuple".
     *
     * @param integer $x
     * @param integer $y
     * @throws BikeException
     */
    public function setPosition(int $x, int $y): void
    {
        if (!$this->board->validatePosition($x, $y)) {
            throw new BikeException("Invalid position ({$x}, {$y})");
        }

        $this->position = ['x' => $x, 'y' => $y];
    }

    /**
     * Get the direction object that corresponds to the
     * direction that the bike is currently facing.
     *
     * @return array
     */
    public function getDirection(): ?array
    {
        if ($this->direction === null) {
            return null;
        }

        return self::DIRECTIONS[$this->direction];
    }

    /**
     * Get the name of the compass direction the bike is facing.
     *
     * @return array
     */
    public function getDirectionName(): ?string
    {
        return $this->getDirection()['name'] ?? null;
    }

    /**
     * Set Bike direction from named compass
     * directions (North, East, South, West).
     *
     * @param string $directionString
     * @throws BikeException
     */
    public function setDirection(string $directionString)
    {
        $directionString = strtolower($directionString);

        switch ($directionString) {
            case 'north':
                $this->direction = self::NORTH;
                break;
            case 'east':
                $this->direction = self::EAST;
                break;
            case 'south':
                $this->direction = self::SOUTH;
                break;
            case 'west':
                $this->direction = self::WEST;
                break;
            default:
                throw new BikeException(
                    "Invalid direction {$directionString}"
                );
                break;
        }
    }

    /**
     * Place bike at the specified position (x, y), facing
     * compass direction (North, East, South, West).
     *
     * @param integer $x
     * @param integer $y
     * @param string $direction
     * @throws BikeException
     */
    public function place(int $x, int $y, string $directionString): void
    {
        $this->setPosition($x, $y);
        $this->setDirection($directionString);
    }

    /**
     * Move the bike one unit in the direction it's currently facing.
     *
     * The bike cannot move to an position that is
     * out of bounds on the Board. Nor can it move
     * if it has not been placed on the Board.
     *
     * @throws BikeException
     */
    public function move(int $steps = 1): void
    {
        $direction = $this->getDirection();

        if ($this->direction === null) {
            throw new BikeException("Bike cannot move if unplaced.");
        }

        // Add the bike direction vector to it's position
        // to calculate it's new position on the Board.
        $newPositionX = $this->position['x'] + ($direction['x'] * $steps);
        $newPositionY = $this->position['y'] + ($direction['y'] * $steps);

        if (!$this->board->validatePosition($newPositionX, $newPositionY)) {
            throw new BikeException(
                "Bike cannot move to invalid position ({$newPositionX}, {$newPositionY})"
            );
        }

        $this->setPosition($newPositionX, $newPositionY);
    }

    /**
     * Move Bike's compass direction anti-clockwise
     * from it's current direction.
     *
     * Bike cannot rotate if it is not yet placed.
     *
     * @throws BikeException
     */
    public function rotateLeft(): void
    {
        $direction = $this->direction;

        if ($this->direction === null) {
            throw new BikeException("Bike cannot rotate left if unplaced.");
        }

        // Shift direction backwards while wrapping around at array boundaries.
        // There is some duplication here with rotateLeft().
        $upperLimit = count(self::DIRECTIONS) - 1;
        $lowerLimit = 0;

        if ($direction <= $lowerLimit) {
            $this->direction = $upperLimit;
        } else {
            $this->direction = $direction - 1;
        }
    }

    /**
     * Move Bike's compass direction clockwise
     * from it's current direction.
     *
     * Bike cannot rotate if it is not yet placed.
     *
     * @throws BikeException
     */
    public function rotateRight(): void
    {
        $direction = $this->direction;

        if ($this->direction === null) {
            throw new BikeException("Bike cannot rotate right if unplaced.");
        }

        // Shift direction forwards while wrapping around at array boundaries.
        // There is some duplication here with rotateLeft().
        $upperLimit = count(self::DIRECTIONS) - 1;
        $lowerLimit = 0;

        if ($direction >= $upperLimit) {
            $this->direction = $lowerLimit;
        } else {
            $this->direction = $direction + 1;
        }
    }

    /**
     * Report the Bike's current position and direction.
     *
     * @return string
     * @throws BikeException
     */
    public function report(): string
    {
        $position = $this->getPosition();
        $direction = $this->getDirection();

        if ($direction === null || $position === null) {
            throw new BikeException("Bike cannot report if unplaced.");
        }

        return implode(',', [
            $position['x'],
            $position['y'],
            strtoupper($direction['name']),
        ]);
    }
}
