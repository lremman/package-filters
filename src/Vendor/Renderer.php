<?php

namespace Starlight\PackageFilters\Vendor;

use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class Renderer {

    /**
     * Collection of FilterElement entity
     * @var Illuminate\Support\Collection
     */
    protected static $filterElements;

    /**
     * Family instance
     * @var Family
     */
    protected static $family;

    /**
     * Cloned input Builder
     * @var Illuminate\Database\Eloquent\Builder
     */
    protected static $inputQueryState;

    /**
     * [$renderedElements description]
     * @var [type]
     */
    protected static $renderedElements;


    /**
     * @param Builder    $inputQueryState
     * @param Family     $family
     * @param Illuminate\Support\Collection $filterElements
     */
    public function __construct(Builder $inputQueryState, Family $family, Collection $filterElements)
    {
        self::$renderedElements = collect();

        $this->setInputQueryState($inputQueryState);
        $this->setFamily($family);
        $this->setFilterElements($filterElements);

        $this->render();

        return $this;
    }

    /**
     * @param Family $family
     */
    protected function setFamily(Family $family)
    {
        return self::$family = $family;
    }

    /**
     * @return Family
     */
    public function getFamily()
    {
        return self::$family;
    }

    /**
     * Collection of FilterElement entity
     * @param Illuminate\Support\Collection $filterElements
     */
    protected function setFilterElements(Collection $filterElements)
    {
        return self::$filterElements = $filterElements;
    }

    /**
     * Collection of FilterElement entity
     * @return  Illuminate\Support\Collection
     */
    public function getFilterElements()
    {
        return self::$filterElements;
    }

    /**
     * @param Builder $inputQueryState
     */
    protected function setInputQueryState(Builder $inputQueryState)
    {
        return self::$inputQueryState = $inputQueryState;
    }

    /**
     * @return  Builder
     */
    public function getInputQueryState()
    {
        return self::$inputQueryState;
    }

    /**
     * @return  void
     */
    public function render()
    {
        $family = $this->getFamily();
        $filterElements = $this->getFilterElements();

        foreach($family->getPriorityMap() as $itemName)
        {
            $filterElement = $filterElements->get($itemName);

            try {
                $this->renderItem($filterElement);
            } catch(Exception $e) {
                throw new Exception('StarlightFilter: Render error in [' . $filterElement->getName() . "]\n" . $e->getMessage());
            }
        }
    }

    /**
     * @param  FilterElement $filterElement
     * @return RenderedElement
     */
    public function renderItem(FilterElement $filterElement)
    {
        $parentElement = $this->getParent($filterElement);

        $renderer = $filterElement->getRenderer();

        // If renderer is not defined
        if(!$renderer) {
            return $this->addRenderedElement(
                new RenderedElement(
                    $filterElement->getName(),
                    collect(),
                    $filterElement->getValue()
                )
            );
        }

        $rendererArgumentsCount = (new \ReflectionFunction($renderer))
            ->getNumberOfParameters();

        if($rendererArgumentsCount == 2) {
            $parent_value = $parentElement ? $parentElement->getValue() : null;
            $items = $this->getParentItems($filterElement);
            $renderedItems = call_user_func($renderer, $parent_value, $items);

        } elseif($rendererArgumentsCount == 1) {

            $parent_value = $parentElement ? $parentElement->getValue() : null;
            $renderedItems = call_user_func($renderer, $parent_value);

        } else {

            $renderedItems = call_user_func($renderer);

        }

        // If renderer return no value
        if($renderedItems === null) {
            throw new Exception('Renderer must return a value');
        }

        return $this->addRenderedElement(
            new RenderedElement(
                $filterElement->getName(),
                $renderedItems,
                $filterElement->getValue()
            )
        );
    }

    /**
     * @param  FilterElement $filterElement
     * @return Illuminate\Database\Eloquent\Collection
     */
    protected function getParentItems(FilterElement $filterElement)
    {
        $parentFilterElement = $this->getParent($filterElement);

        if(!$parentFilterElement) {
            $query = $this->getInputQueryState();
            $parentFilterElement = new FilterElement(null, $query);
        }

        $query = $parentFilterElement->getQueryCurrentState();

        $items = $query->get();

        return $items;
    }

    /**
     * @param  FilterElement
     * @return FilterElement / null
     */
    protected function getParent(FilterElement $currentElement)
    {
        $filterElements = $this->getFilterElements();

        $parent = $currentElement->getParent();

        if(!$parent) {
            return null;
        }

        if(!$parentElement = $filterElements->get($parent)) {
            throw new Exception('StarlightFilter: Parent with given key [' . $parent . '] could not be found');
        }

        return $parentElement;
    }

    /**
     * @param  string $name
     * @return RenderedElement
     */
    public function getRenderedElement($name)
    {
        if(!$enderedElement = self::$renderedElements->get($name)) {
            throw new Exception('StarlightFilter: Filter with given key [' . $name . '] could not be found');
        }

        return $enderedElement;
    }

    /**
     * @return  Collection of FilterElement entity
     */
    public function getAllItems()
    {
        $items = self::$renderedElements;
        return $items;
    }

    /**
     * @param RenderedElement $renderedElement
     */
    protected function addRenderedElement(RenderedElement $renderedElement)
    {
        $name = $renderedElement->name;
        return self::$renderedElements->put($name, $renderedElement);
    }

}

