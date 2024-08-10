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
        // Copy default configuration class to the App Configuration with some replacement namesapce, baseclass, extending.
        $this->copyConfig("Config/Flame.php", [
            "namespace Flame\\Config"              => "namespace Config",
            "use CodeIgniter\\Config\\BaseConfig;" => "use Flame\\Config\\Flame as FlameConfig;",
            "extends BaseConfig"                   => "extends FlameConfig",
        ]);
        CLI::write(CLI::color("Finished!", "green"));
    }

    protected function copyConfig(string $path, ?array $replaces)
    {
        $source = __DIR__ . "/../" . $path;
        $destination = APPPATH . $path;

        // Already exists
        if (is_file($destination)) {
            CLI::write(CLI::color("Configuration file already exists, skip copy.", "yellow"));
            return;
        }

        // Refers CodeIgniter Shield installation.
        // @ref https://github.com/codeigniter4/shield/blob/develop/src/Commands/Setup.php
        $buffer = file_get_contents($source);
        if ($replaces !== null) {
            $buffer = strtr($buffer, $replaces);
        }

        $write = file_put_contents($destination, $buffer);
        if ($write === false) {
            CLI::write(CLI::color("Failed to copy flame configuration file", "red"));
            return;
        }
        CLI::write(CLI::color("Copy: $source -> $destination", "white"));
    }
}
