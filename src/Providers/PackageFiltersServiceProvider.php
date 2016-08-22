<?php

namespace Starlight\PackageFilters\Providers;

use Packages;
use Starlight\Kernel\Packages\AbstractServiceProvider;
use Starlight\PackageFilters\Filter;

class PackageFiltersServiceProvider extends AbstractServiceProvider
{

    /**
     * @var array
     */
    protected $controllers = [
        'PackageFiltersController',
    ];

    /**
     * @return void
     */
    public function init()
    {

        $this->app->singleton('starlightFilter', function(){
            return new \Starlight\PackageFilters\Vendor\StarlightFilter;
        });



        // $this->addSidebarControl('package-filters', '\Packages\PackageFiltersController@getList', [
        //     'title' => _('PackageFilters'),
        //     'icon' => 'pencil',
        // ]);

        // // awesome injection
        // // $this->registerInjectTpl(['SomeInjectLabel'], 'package-filters::inject.some-tpl', function ($entity) {
        // //     return [];
        // // });

        // // awesome injection
        // // $this->registerInjectTpl(['SomeInjectLabel'], 'package-filters::inject.some-tpl', function ($entity) {
        // //     return [];
        // // });
    }
}
