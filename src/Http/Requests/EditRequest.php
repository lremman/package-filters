<?php

namespace Starlight\PackageFilters\Http\Requests;

use Starlight\Kernel\Packages\AbstractRequest;

class EditRequest extends AbstractRequest
{
    /**
     * @var array
     */
    protected $inject_rules = [];

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|max:255',
            'is_active' => 'required|in:1,0',
        ];
    }
}
