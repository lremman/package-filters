<?php

namespace Starlight\PackageFilters\Http\Controllers;

use Starlight\PackageFilters\Http\Requests;
use Packages;
use Packages\Filter;
use Starlight\Kernel\Packages\AbstractController;

class PackageFiltersController extends AbstractController
{
    /**
     * @return Response
     */
    public function getList()
    {
        $items = Filter::paginate(30);

        return view('package-filters::list', [
            'package_filters' => $items,
        ]);
    }

    /**
     * @return Response
     */
    public function getAdd()
    {
        return view('package-filters::add');
    }

    /**
     * @param  Requests\AddRequest $request
     * @return Response
     */
    public function postAdd(Requests\AddRequest $request)
    {
        // awesome code here

        Filter::create($request->allWithRules());

        return redirect(action('\Packages\PackageFiltersController@getList'))
            ->withMessagesSuccess([ _('Успешно создано') ]);
    }

    /**
     * @param  Filter $package_filters
     * @return Response
     */
    public function getEdit(Filter $package_filters)
    {
        return view('package-filters::edit', [
            'package_filters' => $package_filters,
        ]);
    }

    /**
     * @param  Filter       $package_filters
     * @param  Requests\EditRequest  $request
     * @return Response
     */
    public function postEdit(Filter $package_filters, Requests\EditRequest $request)
    {
        // awesome code here

        $package_filters->fill($request->allWithRules());
        $package_filters->save();

        return redirect(action('\Packages\PackageFiltersController@getList'))
            ->withMessagesSuccess([ _('Успешно обновлено') ]);
    }

    /**
     * @param  Filter       $package_filters
     * @param  Requests\EditRequest  $request
     * @return Response
     */
    public function deleteDelete(Filter $package_filters)
    {
        // awesome code here

        $package_filters->delete();

        return redirect(action('\Packages\PackageFiltersController@getList'))
            ->withMessagesSuccess([ _('Успешно удалено') ]);
    }
}
