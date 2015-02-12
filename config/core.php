<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Configuration values for Esensi\Core components package
    |--------------------------------------------------------------------------
    |
    | The following lines contain the default configuration values for the
    | Esensi\Core components package. You can publish these to your project for
    | modification using the following Artisan command:
    |
    | php artisan config:publish esensi/core
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Application aliases
    |--------------------------------------------------------------------------
    |
    | The following configuration options allow the developer to map aliases to
    | controllers and models for easier customization of how Esensi handles
    | requests related to this components package. These aliases are loaded by
    | the service provider for this components package. If your app actually
    | makes use of a class by the same alias then simply comment out the
    | alias here so that the local class may be used instead.
    |
    */
    'aliases' => [
        'App\Exceptions\RepositoryException'          => 'Esensi\Core\Exceptions\RepositoryException',
        'App\Http\Controllers\AdminController'        => 'Esensi\Core\Http\Controllers\AdminController',
        'App\Http\Controllers\ApiController'          => 'Esensi\Core\Http\Controllers\ApiController',
        'App\Http\Controllers\PublicController'       => 'Esensi\Core\Http\Controllers\PublicController',
        'App\Http\Middleware\AuthenticatedRedirector' => 'Esensi\Core\Http\Middlewares\AuthenticatedRedirector',
        'App\Http\Middleware\AuthenticationVerifier'  => 'Esensi\Core\Http\Middlewares\AuthenticationVerifier',
        'App\Http\Middleware\CsrfTokenVerifier'       => 'Esensi\Core\Http\Middlewares\CsrfTokenVerifier',
        'App\Http\Middleware\EventLogger'             => 'Esensi\Core\Http\Middlewares\EventLogger',
        'App\Http\Middleware\RateLimiter'             => 'Esensi\Core\Http\Middlewares\RateLimiter',
        'App\Http\Requests\Request'                   => 'Esensi\Core\Http\Requestss\Request',
        'App\Models\Collection'                       => 'Esensi\Core\Models\Collection',
        'App\Models\Model'                            => 'Esensi\Core\Models\Model',
        'App\Models\SoftModel'                        => 'Esensi\Core\Models\SoftModel',
        'App\Providers\RateLimiterProvider'           => 'Esensi\Core\Providers\RateLimiterServiceProvider',
        'App\Repositories\Repository'                 => 'Esensi\Core\Repositories\Repository',
        'App\Repositories\TrashableRepository'        => 'Esensi\Core\Repositories\TrashableRepository',
        'App\Seeders\Seeder'                          => 'Esensi\Core\Seeders\Seeder',
    ],

    /*
    |--------------------------------------------------------------------------
    | Component packages to load
    |--------------------------------------------------------------------------
    |
    | The following configuration options tell Esensi which component packages
    | are available. This can be useful for many things but is specifically used
    | by the template engine to determine how to render the administrative UI.
    |
    */

    'packages' => [
        'user'
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuration of component package route prefixes
    |--------------------------------------------------------------------------
    |
    | The following configuration options alter the route prefixes used for
    | the administrative backend, API, and component package URLs.
    |
    */

    'prefixes' => [
        'admin'         => 'admin',
        'public'        => '',
        'api' => [
            'latest'    => 'api',
            'v1'        => 'api/v1',
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Interfaces to be enabled by packages
    |--------------------------------------------------------------------------
    |
    | The following configuration options alter which interfaces are included,
    | effectively allowing the developer to not use some or all of the default
    | interfaces available. This is used primarily by the routes configurations.
    |
    */

    // UIs
    'ui' => [
        'admin'  => true,
        'public' => true,
    ],

    // APIs
    'api' => [
        'public' => true,
        'admin'  => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Views to be used by core package
    |--------------------------------------------------------------------------
    |
    | The following configuration options alter which package handles the
    | views, and which views are used specifically by each function.
    |
    */

    'views' => [

        // Public views
        'public' => [

            'index'       => 'esensi/core::core.public.index',
            'missing'     => 'esensi/core::core.public.missing',
            'whoops'      => 'esensi/core::core.public.whoops',
            'modal'       => 'esensi/core::core.admin.modal',
            'maintenance' => 'esensi/core::core.public.maintenance',
        ],

        // Admin views
        'admin' => [

            'modal'   => 'esensi/core::core.admin.modal',
        ],
    ],

    'partials' => [

        // Public partials
        'public' => [

            'errors'  => 'esensi/core::core.admin.partials.errors',
            'footer'  => 'esensi/core::core.public.partials.footer',
            'header'  => 'esensi/core::core.public.partials.header',
        ],

        // Admin partials
        'admin' => [

            'account'      => 'esensi/core::core.admin.partials.dropdown',
            'drawer'       => 'esensi/core::core.admin.partials.drawer',
            'errors'       => 'esensi/core::core.admin.partials.errors',
            'footer'       => 'esensi/core::core.admin.partials.footer',
            'header'       => 'esensi/core::core.admin.partials.header',
            'logout'       => 'esensi/core::core.admin.partials.logout',
            'bulk_actions' => 'esensi/core::core.admin.partials.bulk-actions',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Dashboard link
    |--------------------------------------------------------------------------
    |
    | The following configuration option specifies whether the backend should
    | show or hide the dashboard menu item.
    |
    */

    'dashboard' => false,

    /*
    |--------------------------------------------------------------------------
    | Logout link
    |--------------------------------------------------------------------------
    |
    | The following configuration option specifies whether the backend should
    | show logout menu item (true) or simply redirect to the frontend (false).
    |
    */

    'logout' => false,

    /*
    |--------------------------------------------------------------------------
    | Attribution link
    |--------------------------------------------------------------------------
    |
    | The following configuration option specifies whether the backend should
    | show or hide the attribution menu item.
    |
    */

    'attribution' => [

        'enable' => true,
        'url'    => 'http://esen.si',
        'name'   => 'Powered by Esensi',
    ],

    /*
    |--------------------------------------------------------------------------
    | Meta data
    |--------------------------------------------------------------------------
    |
    | The following configuration options provide HTML meta data tags to the
    | header templates.
    |
    */

    'metadata' => [

        'keywords'    => 'emersonmedia esensi laravel boilerplate framework platform',
        'description' => 'Esensi is an awesome boilerplate application.',
        'author'      => 'Esensi',
        'generator'   => gethostname(),
    ],

    /*
    |--------------------------------------------------------------------------
    | Rate limit settings
    |--------------------------------------------------------------------------
    |
    | The following configuration option specifies whether or not the rate
    | limiter should be enabled and how it should behave. The default behavior
    | is that it is enabled and set to reasonable levels to control
    | potential hacking threats. More than 10 requests to the same page per
    | minute will generate a 10 minute ban for that IP address.
    |
    */

    'rates' => [

        // Should the limiter be enabled?
        'enabled' => true,

        // Should limits be based on unique routes?
        'routes'  => true,

        // Should route uniqueness be based on the route parameters?
        'parameters'  => true,

        // Request per period
        'limit'   => 10,

        // Period duration in minutes
        'period'  => 1,

        // Cache settings
        'cache' => [

            // Namespace for tags
            'tag'     => 'xrate',

            // Cache storage settings
            'driver'  => 'file',
            'table'   => 'cache',

            // Timeout (in minutes) an IP should be blocked
            'timeout' => 10,
        ],
    ],

];