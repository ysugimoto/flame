<?php

declare(strict_types=1);

namespace Flame\Commands;

use CodeIgniter\CLI\CLI;
use CodeIgniter\CLI\BaseCommand;

class Setup extends BaseCommand
{
    protected $group = "Flame";
    protected $name = "flame:setup";
    protected $description = "Initial setup for Flame library.";
    protected $usage = "flame:setup";

    public function run(array $params)
    {
        $this->copyConfig("Config/Flame.php");
        CLI::write(CLI::color("Finished!", "green"));
    }

    protected function copyConfig(string $path)
    {
        $source = __DIR__ . "/../" . $path;
        $destination = APPPATH . $path;

        // Already exists
        if (is_file($destination)) {
            CLI::write(CLI::color("Configuration file already exists, skip copy.", "yellow"));
            return;
        }

        $write = file_put_contents($destination, file_get_contents($source));
        if ($write === false) {
            CLI::write(CLI::color("Failed to copy flame configuration file", "red"));
            return;
        }
        CLI::write(CLI::color("Copy: $source -> $destination", "white"));
    }
}
