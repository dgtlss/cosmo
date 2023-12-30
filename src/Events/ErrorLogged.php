<?php

namespace Dgtlss\Cosmo\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Dgtlss\Cosmo\Models\CosmoError;

Class ErrorLogged
{
    use Dispatchable, SerializesModels;

    public $cosmoError;

    public function __construct(CosmoError $cosmoError)
    {
        $this->cosmoError = $cosmoError;
    }
}