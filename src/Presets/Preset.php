<?php

namespace SebastiaanLuca\Preset\Presets;

use Illuminate\Foundation\Console\PresetCommand;
use Illuminate\Foundation\Console\Presets\Preset as BasePreset;
use SebastiaanLuca\Preset\ExecutesShellCommands;
use function SebastiaanLuca\Preset\project_config;

class Preset extends BasePreset
{
    use ExecutesShellCommands;

    /**
     * @var \Illuminate\Foundation\Console\PresetCommand
     */
    protected $command;

    /**
     * @param \Illuminate\Foundation\Console\PresetCommand $command
     */
    public function __construct(PresetCommand $command)
    {
        $this->command = $command;
    }

    /**
     * @param \Illuminate\Foundation\Console\PresetCommand $command
     */
    public static function install(PresetCommand $command) : void
    {
        (new static($command))->run();
    }

    public function run() : void
    {
        foreach (project_config('actions') as $name => $action) {
            $this->command->task($name, function () use ($action) {
                return app($action, ['command' => $this->command])->execute();
            });
        }

        // TODO: update user model and update reference to users model in config file
        // TODO: change bash alias new command to use preset package dev-develop instead of local repo

        $this->command->info('Project successfully scaffolded!');
        $this->command->comment('Don\'t forget to review your composer.json, .env, and README files.');
    }
}
