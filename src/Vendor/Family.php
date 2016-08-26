<?php

namespace Starlight\PackageFilters\Vendor;

use Illuminate\Support\Collection;

class Family {

    /**
     * Collection of all alements and his parents
     * @var  Illuminate\Support\Collection
     */
    protected static $map;

    /**
     * Collection of all family map, builded on weight
     * @var  Illuminate\Support\Collection
     */
    protected static $familyMap;

    /**
     * Collection of FilterElement entity
     * @param  Illuminate\Support\Collection $filterElements
     */
    public function __construct(Collection $filterElements)
    {
        $map = $this->buildMap($filterElements);

        $this->setMap($map);

        $familyMap = $this->buildFamilyMap();

        $this->setFamilyMap($familyMap);

        return $this;
    }

    /**
     * @param  Illuminate\Support\Collection $map
     */
    protected function setMap(Collection $map)
    {
        self::$map = $map;
    }

    /**
     * @return  Illuminate\Support\Collection
     */
    public function getMap()
    {
        return self::$map;
    }

    /**
     * @param  Illuminate\Support\Collection $familyMap
     */
    protected function setFamilyMap(Collection $familyMap)
    {
        self::$familyMap = $familyMap;
    }

    /**
     * @return  Illuminate\Support\Collection
     */
    public function getFamilyMap()
    {
        return self::$familyMap;
    }

    /**
     * @return  Illuminate\Support\Collection
     */
    public function getPriorityMap()
    {
        $familyMap = $this->getFamilyMap();
        $items = collect();

        foreach ($familyMap  as $rangeItems) {
            foreach($rangeItems as $item) {
                $items->push($item);
            }
        }

        return $items;
    }

    /**
     * @param  string $name \\Element name
     * @return  Illuminate\Support\Collection
     */
    public function getFamilyChildren($name)
    {
        $children = [];
        $isAfter = 0;

        $familyMap = $this->getFamilyMap();

        foreach($familyMap as $range) {
            foreach($range as $filterName) {
                if($name == $filterName) {
                    $isAfter = 1;
                    continue;
                } elseif ($isAfter) {
                    $children[] = $filterName;
                }
            }
        }

        return collect($children);
    }

    /**
     * @param  Illuminate\Support\Collection $filterElements
     * @return  Illuminate\Support\Collection
     */
    protected function buildMap(Collection $filterElements)
    {
        $map = collect();

        foreach ($filterElements as $item){
            $map->put($item->getName(), $item->getParent());
        }

        return $map;
    }

    /**
     * @return  Illuminate\Support\Collection
     */
    protected function buildFamilyMap()
    {
        $map = $this->getMap();

        $familyMap = collect();

        foreach($map as $parent) {
            $children = $this->getChildren($parent);
            $familyMap->put(implode($children, '.'), $children);
        }

        return $familyMap;
    }

    /**
     * @param  string $parent
     * @return  array
     */
    protected function getChildren($parent)
    {
        $map = $this->getMap();

        $children = [];

        foreach($map as $mapElement => $mapParent)
        {
            if($parent == $mapParent) {
                $children[] = $mapElement;
            }
        }

        return $children;
    }

}
