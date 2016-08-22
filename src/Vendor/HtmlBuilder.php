<?php

namespace Starlight\PackageFilters\Vendor;

use Exception;
use Form;

class HtmlBuilder {

    /**
     * @param  string  $name
     * @param  RenderedElement  $renderedElement
     * @param  array  $options
     * @return string  rendered html code
     */
    public static function buildSelect($name, RenderedElement $renderedElement, array $options)
    {

        $list = $renderedElement->items;

        if($list instanceof \Illuminate\Support\Collection) {
            $list = $list->toArray();
        }

        $selected = $renderedElement->active;

        if (!is_string($title = $name)) {

            $name = key($title);
            $title = $title[$name];
            $list = ['' => $title] + $list;
        }

        $formElement = Form::select($name, $list, $selected, $options);

        return $formElement;
    }
}
