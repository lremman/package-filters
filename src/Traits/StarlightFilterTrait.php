<?php

namespace Starlight\PackageFilters\Traits;

use Starlight\PackageFilters\Filter;

trait StarlightFilterTrait {

    public static function bootStarlightFilterTrait()
    {
        Filter::setQuery(self::query());
    }

    /**
     *
     */
    public function scopeFilter($query, $name, $callback)
    {
        Filter::register($name, $callback);

        return $query;
    }
}

