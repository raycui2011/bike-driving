<?php declare(strict_types=1);

namespace BikeDriving\Controllers;

use BikeDriving\Exceptions\BikeException;
use BikeDriving\Models\Board;
use BikeDriving\Models\Bike;

/**
 *  Recieves commands as string and runs the appropriate bike functions.
 */
class BikeController
{
    protected $bike;
    private static $instance = null;

    public function __construct()
    {
        $board = new Board(7, 7);
        $this->bike = new Bike($board);
    }

    /**
     * Give the bike a command from string and optional array of arguments.
     *
     * @param string $command
     * @param array|null $arguments
     * @param bool $verbose
     */
    public function run(
        string $command,
        array $arguments = null,
        bool $verbose = false
    ) {
        $command = strtolower($command);

        try {
            switch ($command) {
                case 'place':
                    // Validate place command was given with
                    // all three (X,Y,DIR) arguments and X and Y
                    // are valid integer values.
                    // This could probably be tidied up.
                    if (count($arguments) >= 3
                        && is_numeric($arguments[0])
                        && is_numeric($arguments[1])
                    ) {
                      //var_dump($arguments);exit;
                        $this->bike->place(
                            intval($arguments[0]), // X
                            intval($arguments[1]), // Y
                            $arguments[2] // Direction
                        );
                        //var_dump($this->bike->report());
                    }
                    break;
                case 'forward':
                    // Optional no. steps argument
                    $steps = 1;
                    if (isset($arguments[0]) && is_numeric($arguments[0])) {
                        $steps = intval($arguments[0]);
                    }
                    $this->bike->move($steps);
                    break;
                case 'turn_left':
                    $this->bike->rotateLeft();
                    break;
                case 'turn_right':
                    $this->bike->rotateRight();
                    break;
                case 'gps_report':
                    var_dump($this->bike->report());
                    break;
                default:
                    break;
            }
        } catch (BikeException $e) {
            //if ($verbose) {
                print("Ignored \"{$command}\": " . $e->getMessage(). "\n");
            //}
        }
    }
}
