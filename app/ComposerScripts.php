<?php

namespace App;

use Dotenv\Dotenv;
use Composer\Script\Event;
use Illuminate\Foundation\ComposerScripts as BaseScripts;

class ComposerScripts extends BaseScripts
{
    /**
     * Handle the post-autoload-dump Composer event.
     *
     * @param  \Composer\Script\Event  $event
     * @return void
     */
    public static function postAutoloadDump(Event $event)
    {
        parent::postAutoloadDump($event);

        if (! file_exists('.env')) {
            copy('.env.example', '.env');
            echo shell_exec('php artisan key:generate');
        }

        static::deleteHelperFiles();

        if ($event->isDevMode()) {
            echo shell_exec('php artisan ide-helper:generate');
            echo shell_exec('php artisan ide-helper:eloquent');
            Dotenv::create(getcwd())->load();
            if (env('APP_ENV') === 'local') {
                echo shell_exec('php artisan ide-helper:models --nowrite --dir="app/Models"');
            }
            echo shell_exec('php artisan ide-helper:meta');
        }

    }

    private static function deleteHelperFiles()
    {
        $names = [
            '.phpstorm.meta.php',
            '_ide_helper.php',
            '_ide_helper_models.php'
        ];

        foreach ($names as $name) {
            if (file_exists($name)) {
                @unlink($name);
            }
        }
    }
}
