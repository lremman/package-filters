<?php

namespace Starlight\PackageFilters\Vendor;

use Input;
use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class FilterElement {

    /**
     * @var Illuminate\Database\Eloquent\Builder
     */
    protected $query;

    /**
     * Cloned input query by this range
     * @var Illuminate\Database\Eloquent\Builder
     */
    protected $queryCurrentState;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $value;

    /**
     * @var Illuminate\Support\Collection
     */
    protected $renderer;

    /**
     * Parent name
     * @var string
     */
    protected $parent;

    /**
     * @param string  $name
     * @param Builder $query
     */
    public function __construct($name, Builder $query)
    {
        $this->setQuery($query);
        $this->setQueryCurrentState($query);
        $this->setName($name);
        $this->setValue(Input::get($name));
    }

    /**
     * @return Builder
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * @param Builder $query
     */
    protected function setQuery(Builder $query)
    {
        return $this->query = $query;
    }

    /**
     * @return Builder
     */
    public function getQueryCurrentState()
    {
        return $this->queryCurrentState;
    }

    /**
     * @param Builder $query
     */
    protected function setQueryCurrentState(Builder $query)
    {
        return $this->queryCurrentState = $query;
    }

    /**
     * @param  string $value
     */
    protected function setValue($value)
    {
        return $this->value = $value;
    }

    /**
     * @return  string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param  string $name
     */
    protected function setName($name)
    {
        return $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param  Closure $renderer
     */
    protected function setRenderer(Closure $renderer)
    {
        return $this->renderer = $renderer;
    }

    /**
     * @return Closure
     */
    public function getRenderer()
    {
        return $this->renderer;
    }

    /**
     * @param  Closure $callback
     */
    public function addRenderer(Closure $callback)
    {
        $renderer = $this->setRenderer($callback);

        return $renderer;
    }

    /**
     * Closure $callback
     */
    public function addScope(Closure $callback)
    {
        $query = $this->getQuery();
        $value = $this->getValue();

        call_user_func($callback, $query, $value);

        $this->setQueryCurrentState(clone $query);

        return $this->setQuery($query);
    }

    /**
     * @param  string $parent
     */
    public function setParent($parent = null)
    {
        return $this->parent = $parent;
    }

    /**
     * @return  string
     */
    public function getParent()
    {
        return $this->parent;
    }

}
