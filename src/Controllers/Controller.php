<?php

namespace Dgtlss\Cosmo\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

Class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;   
}