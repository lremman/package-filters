<?php

namespace Starlight\PackageFilters\Vendor;

use Illuminate\Support\Collection;

class RenderedElement {

    /**
     * @var string
     */
    public $name;

    /**
     * @var Illuminate\Support\Collection
     */
    public $items;

    /**
     * @var string
     */
    public $active;

    /**
     *
     */
    public function __construct($name, Collection $items, $active = null)
    {
        $this->name = $name;
        $this->items = collect($items);
        $this->active = $active;
    }
}
