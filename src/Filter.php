<?php

namespace Starlight\PackageFilters;

use Illuminate\Support\Facades\Facade;
use Starlight\PackageFilters\Vendor\StarlightFilter;
use Starlight\PackageFilters\Vendor\HtmlBuilder;
use Illuminate\Database\Eloquent\Builder;
use Closure;

class Filter extends FiltersRegister {

    /**
     * @param  Builder $query
     */
    public function createFilter(Builder $query)
    {
        $key = $query->getModel()->getMorphClass();

        $filter = new StarlightFilter;
        $filter->setQuery($query);

        $this->registerFilter($key, $filter);
    }

    /**
     * @param  Builder $query
     * @param  string $name
     * @param  Closure $callback
     */
    public function register(Builder $query, $name, Closure $callback)
    {
        $key = $query->getModel()->getMorphClass();

        $this->setRelation($key, $name);

        $filter = $this->getFilter($key);

        $filter->register($name, $callback);
    }


    /**
     *
     * @param  string $name
     * @return RenderedElement
     */
    public function getRenderedElement($name)
    {
        $filter = $this->getFilterByElementName($name);

        $renderedElement = $filter->getRenderedElement($name);

        return $renderedElement;

    }

    /**
     * @param  string $name
     * @param  string $title
     * @param  array $attributes
     * @return  string (html code)
     */
    public function renderSelect($name, $title, array $attributes = [])
    {
        $renderedElement = $this->getRenderedElement($name);

        $formElement = HtmlBuilder::buildSelect([$name => $title], $renderedElement, $attributes);

        return $formElement;
    }

    /**
     * @param  string $name
     * @param  array $attributes
     * @return  string (html code)
     */
    public function renderInputText($name, array $attributes = [])
    {
        $renderedElement = $this->getRenderedElement($name);

        $formElement = HtmlBuilder::buildInputText($name, $renderedElement, $attributes);

        return $formElement;
    }
}

