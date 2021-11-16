
#!/usr/bin/env php
<?php
    /**
     * Feed the RobotController the list of commands we pulled from the input file.
     *
     * @param array $commands
     */
    function run(array $commands, bool $verbose = false)
    {
        require __DIR__.'/vendor/autoload.php';

        $controller = new BikeDriving\Controllers\BikeController();

        foreach ($commands as $command) {
            $controller->run(
                $command['name'],
                $command['arguments'] ?? null,
                $verbose
            );
        }
    }

    /**
     * Open the input file and generate a list of commands
     *
     * @param string $filePath
     * @return array $commands
     */
    function process(string $filePath)
    {
        $commands = [];
        $filePath = file_get_contents($filePath);
        $lines = explode(PHP_EOL, $filePath);

        foreach ($lines as $line) {
            // Match commands in format " COMMAND ARGS ".
            // This regex pattern is very loose and could probably be revised.
            $result = preg_match('/\s*(\S*)\s*(\S*)\s*/', $line, $matches);

            if (!$result) {
                continue;
            }

            $command = $matches[1];
            $arguments = $matches[2] ?? null;

            if (!$command) {
                continue;
            }

            if ($arguments) {
                $arguments = explode(',', $arguments);
            } else {
                $arguments = null;
            }

            array_push($commands, [
                'name' => $command,
                'arguments' => $arguments
            ]);
        }

        return $commands;
    }

    /**
     * Print usage message to the console.
     *
     * @param string $name
     */
    function usage(string $name)
    {
        print("usage: {$name} input_file\n\n");
        print("  input_file\t\tThe filepath containing robot commands to be processed\n\n");
        print("options:\n\n");
        print("  -v --verbose\t\tDisplay robot warnings\n\n");
        print("  -h --help\t\tShow this message\n\n");
    }

    /**
     * Validate and process command line arguments. Returns the input file path.
     *
     * @param integer $argc
     * @param array $argv
     * @return string $filePath
     */
    function args(int $argc, array $argv)
    {
        if ($argc < 2) {
            print("ABORT: You must specify an input file\n\n");
            usage($argv[0]);
            exit(1);
        }

        if (in_array($argv[1], ['--help', '-h'])) {
            usage($argv[0]);
            exit();
        }

        $filePath = $argv[1];

        if (!file_exists($filePath)) {
            print("ABORT: {$filePath} is not a valid input file\n\n");
            usage($argv[0]);
            exit(1);
        }

        if (isset($argv[2]) && in_array($argv[2], ['--verbose', '-v'])) {
            print("VERBOSE\n");
            $verbose = true;
        }

        return [
            'file_path' => $filePath,
            'verbose' => $verbose ?? false,
        ];
    }

    /**
     * Run this script.
     *
     * @param integer $argc
     * @param array $argv
     */
    function main(int $argc, array $argv)
    {
        $arguments = args($argc, $argv);

        $commands = process($arguments['file_path']);

        run($commands, $arguments['verbose']);

        exit();
    }

    main($argc, $argv);
