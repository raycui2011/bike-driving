# Bike-Driving
===================

My solution to the Bike Driving PHP challenge.

Installation and Usage
-----------

### Composer

Install composer https://getcomposer.org/download/. 

Use composer to install our dependencies. Composer is installed globally
in the following examples.

``` bash
composer install
```

### Testing

``` bash
composer test
```

### Usage

Any text file containing Robot commands can be used.

``` bash
php bike-driving.php commands/corner-to-corner
```

### Commands

The following commands will be accepted by the Robot. Invalid or
incomplete commands will be ignored.

```
PLACE X,Y,DIR
MOVE STEPS?
TURN_LEFT
TURN_RIGHT
GRS_REPORT
```

#### Place

The `PLACE` command takes three arguments `X,Y,DIR`. `X` and `Y` give
the Bike's position and `DIR` gives the compass direction that it will
face. The Robot will ignore any other command if it is not yet placed.

#### FORWARD

`MOVE` will ask the Robot to change it's position one unit in the 
direction it's facing. Optionally, you can ask the Robot to move
multiple units by providing the `STEPS` argument.

#### TURN_LFET and TURN_RIGTH

`TURN_LFET` and `TURN_RIGTH` will tell the Robot to rotate in each direction.

#### GRS_REPORT

`GRS_REPORT` outputs the Bike's current position and direction as `X,Y,DIR`.

### The Board

The Robot's `X,Y` position is bounded by a 5x5 Board. The south-west
corner has the position `0,0` and the north-east corner has the position
`6,6`. The `FORWARD` and `PLACE` commands will be ignored if it would
result in a position that is out of bounds.

Example Input and Output
------------------------

### Corner to corner

    PLACE 0,0,NORTH
    FORWARD
    FORWARD
    FORWARD
    FORWARD
    TURN_RIGHT
    FORWARD
    FORWARD
    FORWARD
    FORWARD
    GPS_REPORT


Expected output:

    4,4,EAST

### Spin left

    PLACE 0,0,SOUTH
    TURN_LEFT
    TURN_LEFT
    TURN_LEFT
    TURN_LEFT
    GPS_REPORT


Expected output:

    0,0,SOUTH

### Spin right

    PLACE 0,0,SOUTH
    TURN_RIGHT
    TURN_RIGHT
    TURN_RIGHT
    TURN_RIGHT
    GPS_REPORT



Expected output:

    0,0,SOUTH

### Four corners fast

    PLACE 0,0,NORTH
    FORWARD
    TURN_RIGHT
    FORWARD
    TURN_RIGHT
    FORWARD
    TURN_RIGHT
    GPS_REPORT

Expected output:

    1,0,WEST


