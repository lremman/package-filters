<?php

namespace Starlight\PackageFilters\Vendor;

use Illuminate\Support\Collection;

class RenderedElement {

    /**
     * @var string
     */
    public $name;

    /**
     * @var Illuminate\Support\Collection / array / string
     */
    public $items;

    /**
     * @var string
     */
    public $active;

    /**
     *
     */
    public function __construct($name, $items, $active = null)
    {
        $this->name = $name;
        if(is_array( $items)) {
            $this->items = collect($items);
        } else {
            $this->items = $items;
        }
        $this->active = $active;
    }

}
