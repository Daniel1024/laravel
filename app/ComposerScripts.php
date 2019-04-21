<?php

namespace App;

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

        //echo shell_exec('php artisan package:discover --ansi');

        if (env('COMPOSER_DEV_MODE') !== '0') {
            //echo shell_exec('php artisan clear-compiled --ansi');
            echo shell_exec('php artisan ide-helper:generate');
            //echo shell_exec('php artisan ide-helper:eloquent --ansi');
            if (env('APP_ENV') === 'local') {
                shell_exec('php artisan ide-helper:models -W --dir="app/Models"');
            }
            echo shell_exec('php artisan ide-helper:meta');
        }

    }
}
