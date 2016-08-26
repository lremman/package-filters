<?php

namespace Starlight\PackageFilters\Traits;

use Filter;

trait StarlightFilterTrait {

    public static function bootStarlightFilterTrait()
    {
        Filter::createFilter(self::query());
    }

    /**
     *
     */
    public function scopeFilter($query, $name, $callback)
    {
        Filter::register($query, $name, $callback);

        $query = Filter::getFilterByElementName($name)->getQuery();

        return $query;
    }
}

