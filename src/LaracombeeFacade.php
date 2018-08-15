<?php

namespace Amranidev\Laracombee;

use Illuminate\Support\Facades\Facade;

class LaracombeeFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'Amranidev\Laracombee\Laracombee';
    }
}
