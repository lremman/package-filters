<?php

namespace Starlight\PackageFilters\Providers;

use Packages;
use Starlight\Kernel\Packages\AbstractServiceProvider;

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
            return new \Starlight\PackageFilters\Filter;
        });

        $this->publishAssetsDir(
            $this->root('resources/assets'),
            public_path('pkgs/package-filters')
        );

        $this->lazyAssetsDeclare('starlight-filter', [
            static_asset('pkgs/package-filters/starlight-filter.js'),
        ]);

    }
}
