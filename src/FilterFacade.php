<?php

namespace Starlight\PackageFilters;

use Illuminate\Support\Facades\Facade;

class FilterFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'starlightFilter'; }
}

