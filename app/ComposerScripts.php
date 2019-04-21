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

        if ($event->isDevMode()) {
            echo shell_exec('php artisan ide-helper:generate');
            echo shell_exec('php artisan ide-helper:eloquent');
            Dotenv::create(getcwd())->load();
            if (env('APP_ENV') === 'local') {
                echo 'Se modifica los modelos.'.PHP_EOL;
                shell_exec('php artisan ide-helper:models --write --dir="app/Models/"');
            }
            echo shell_exec('php artisan ide-helper:meta');
        }

    }
}
