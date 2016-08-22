<?php

namespace Starlight\PackageFilters\Vendor;

use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class StarlightFilter {

    /**
     * Collection of FilterElement entity
     * @var Illuminate\Support\Collection
     */
    protected static $filterElements;

    /**
     * Renderer instance
     * @var Renderer
     */
    protected static $renderer;

    /**
     * @var Illuminate\Database\Eloquent\Builder
     */
    protected static $query;

    /**
     * Cloned input Builder
     * @var Illuminate\Database\Eloquent\Builder
     */
    protected static $inputQueryState;

    /**
     * Family instance
     * @var Family
     */
    protected static $family;


    /**
     *
     */
    public function __construct()
    {
        static::$filterElements = collect();
    }

    /**
     *
     */
    public function getAllRendered()
    {
        $this->renderItems();
        $renderer = $this->getRenderer();

        return $renderer->getAll();
    }

    /**
     *
     */
    public function renderSelect($name, $title, array $attributes = [])
    {
        $this->renderItems();

        $renderer = $this->getRenderer();

        $renderedElement = $renderer->getRenderedElement($name);

        $formElement = HtmlBuilder::buildSelect([$name => $title], $renderedElement, $attributes);

        return $formElement;
    }

    /**
     * @return  Illuminate\Database\Eloquent\Builder
     */
    public function getQuery()
    {
        return self::$query;
    }

    /**
     * @param Illuminate\Database\Eloquent\Builder $query
     */
    public function setQuery(Builder $query)
    {
        static::$inputQueryState = clone $query;
        return static::$query = $query;
    }

    /**
     * @param Family $family
     */
    public function setFamily(Family $family)
    {
        return static::$family = $family;
    }

    /**
     * @return Family
     */
    public function getFamily()
    {
        return self::$family;
    }

    /**
     * @param Renderer $renderer
     */
    protected function setRenderer(Renderer $renderer)
    {
        return self::$renderer = $renderer;
    }

    /**
     * @return  Renderer
     */
    public function getRenderer()
    {
        return self::$renderer;
    }

    /**
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function getInputQueryState()
    {
        return self::$inputQueryState;
    }

    /**
     * @param string $name
     * @param Closure $callback
     * @return void
     */
    public function register($name, $callback)
    {
        $query = $this->getQuery();

        $filter = new FilterElement($name, $query);

        call_user_func($callback, $filter);

        static::$filterElements->put($name, $filter);
    }

    /**
     * Collection of FilterElement
     * @return Illuminate\Support\Collection
     */
    protected function getFilterElements()
    {
        return static::$filterElements;
    }

    /**
     * Render all registred callbacks
     */
    protected function renderItems()
    {
        if($this->getRenderer()) {
            return;
        }

        $filterElements = $this->getFilterElements();

        $family = $this->setFamily(new Family($filterElements));

        $inputQueryState = $this->getInputQueryState();

        $this->setRenderer(new Renderer($inputQueryState, $family, $filterElements));
    }

}
