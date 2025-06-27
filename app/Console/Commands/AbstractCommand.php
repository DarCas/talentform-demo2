<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

abstract class AbstractCommand extends Command
{
    protected function clear(): void
    {
        passthru(strncasecmp(PHP_OS, 'WIN', 3) === 0 ? 'cls' : 'clear');
    }
}
