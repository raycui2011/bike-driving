<?php declare(strict_types=1);

namespace BikeDriving\Models;

use BikeDriving\Exceptions\BoardException;

/**
 *  Class for calculating valid positions based on initial Board dimensions.
 */
class Board
{
    protected $minX;
    protected $maxX;
    protected $minY;
    protected $maxY;

    public function __construct(int $xDimension, int $yDimension)
    {
        if ($xDimension < 0 || $yDimension < 0) {
            throw new BoardException("Invalid board dimension ({$xDimension}, {$yDimension})");
        }

        // First position is always (0, 0)
        $this->minX = 0;
        $this->minY = 0;

        // Board positions are zero indexed so we need to
        // subtract one from the given x and y dimensions.
        $this->maxX = $xDimension - 1;
        $this->maxY = $yDimension - 1;
    }

    /**
     * Determine if a given (x, y) position is valid within this Boards dimensions.
     *
     * @param integer $x
     * @param integer $y
     * @return bool $valid
     */
    public function validatePosition(int $x, int $y): bool
    {
        return $x >= $this->minX
            && $x <= $this->maxX
            && $y >= $this->minY
            && $y <= $this->maxY;
    }
}
