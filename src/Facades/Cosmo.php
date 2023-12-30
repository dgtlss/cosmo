<?php

declare(strict_types=1);
namespace Dgtlss\Cosmo\Facades;
use Illuminate\Support\Facades\Facade;

class Cosmo extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'Cosmo';
    }
}