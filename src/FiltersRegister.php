<?php

namespace Starlight\PackageFilters;

use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Collection;
use Starlight\PackageFilters\Vendor\StarlightFilter;

class FiltersRegister {

    /**
     * Collection of all created filters
     * @var Illuminate\Support\Collection
     */
    protected static $register;

    /**
     * Relation FilterElement names with model morph classes
     * @var Illuminate\Support\Collection
     */
    protected static $relations;

    /**
     * @param  string $key (morph class)
     * @return  StarlightFilter
     */
    public function getFilter($key)
    {
        return static::$register->get($key);
    }

    /**
     * @param  string $name (filter element name)
     * @return  StarlightFilter
     */
    public function getFilterByElementName($name)
    {
        $key = $this->getRelation($name);
        $filter = $this->getFilter($key);

        return $filter;
    }

    /**
     * @param  string $key (morph class)
     * @param  StarlightFilter $filter
     */
    protected function registerFilter($key, StarlightFilter $filter)
    {
        if(!static::$register) {
            static::$register = collect();
        }
        static::$register->put($key, $filter);
    }

    /**
     * @param  string $key (morph class)
     * @param  string $name (filter element name)
     */
    protected function setRelation($key, $name)
    {
        if(!static::$relations) {
            static::$relations = collect();
        }
        static::$relations->put($name, $key);
    }

    /**
     * @param  string $name (filter element name)
     * @return  string
     */
    protected function getRelation($name)
    {
        return static::$relations->get($name);
    }

}

