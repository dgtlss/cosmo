<?php

namespace Dgtlss\Cosmo\Listeners;

use Dgtlss\Cosmo\Events\ErrorLogged;

class UpdatedErrorLog
{
    public function handle(ErrorLogged $event)
    {
        // Do something with the error log
    }
}