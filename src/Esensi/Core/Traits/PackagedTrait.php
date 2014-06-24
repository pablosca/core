<?php namespace Esensi\Core\Traits;

use \Illuminate\Support\Facades\App;
use \Illuminate\Support\Facades\Log;

/**
 * Traits for helping with package configurations
 *
 * @author daniel <daniel@bexarcreative.com>
 * @see \Esensi\Core\Contracts\PackagedInterface
 */
trait PackagedTrait{

    /**
     * The package name
     *
     * @var string
     */
    protected $package = 'core';

    /**
     * The UI name
     *
     * @var string
     */
    protected $ui = 'public';

    /**
     * Setup the layout used by the controller.
     * This is part of Laravel's internal controller
     * layout handlers. It should stay here.
     *
     * @return void
     */
    protected function setupLayout()
    {
        if ( ! is_null($this->layout))
        {
            $this->layout = App::make('view')->make($this->layout);
        }
    }

    /**
     * Generate a subview for the layout
     *
     * @param string $key to view config
     * @param array $data (optional) to be passed to view
     * @param string $name (optional) of content
     * @return \Illuminate\View\View
     */
    protected function content($key, array $data = [], $name = null)
    {
        // Assign a default content name
        $name = is_null($name) ? 'content' : $name;

        // Get the confg line for the view
        $line = $this->config('views.' . $this->ui . '.' . $key);

        // Nest the view into the layout
        $view = App::make('view')->make($this->namespacing() . $line, $data);
        $this->layout->$name = $view;

        return $view;
    }

    /**
     * Generate a modal view
     *
     * @param string $key to view config
     * @param array $data to be passed to view
     * @param string $name (optional) of content
     * @return \Illuminate\View\View
     */
    protected function modal($key, array $data = [], $name = null)
    {
        // Change default layout to modal layout
        $namespace = $this->namespacing();
        $line = $namespace . 'core.views.' . $this->ui . '.modal';

        //Log::info("modal line = " . $line);
        //Log::info("modal config line = " . $this->config($line));

        if (empty($this->config($line)))
        {
            $namespace = "esensi::";
            $line =  $namespace . 'core.views.' . $this->ui . '.modal';
            //Log::info("new modal line = " . $line);
        }

        $this->layout = $namespace . $this->config($line);

        //Log::info("modal layout = " . $this->layout);

        $this->setupLayout();

        // Just return the response from the proxy call
        //Log::info("key = " . $key);
        return $this->content($key, $data, $name);
    }

    /**
     * Get a configuration line
     *
     * @param string $key to config line
     * @param mixed $default (optional)
     * @return mixed
     */
    protected function config($key, $default = null)
    {
        // Get the config file loader
        $loader = App::make('config');

        // Get the root namespace
        $namespace = stripos($key, '::') === false ? $this->namespacing() : '';

        // Get the packaged line
        $line = str_singular($this->package) . '.' .$key;

        // Use local namespaced package
        //Log::info("searching config for '" . $namespace . $line . "'");
        //Log::info("searching config for '" . $namespace . $key . "'");
        //og::info("searching config for '" . $line . "'");
        if( $loader->has($namespace . $line) )
        {
            //Log::info("Found '" . $namespace . $line . "'");
            return $loader->get($namespace . $line);
        }

        // Use package namespaced package
        elseif( $loader->has($namespace . $key) )
        {
            //Log::info("Found '" . $namespace . $key . "'");
            return $loader->get($namespace . $key);
        }

        // Use global namespaced package
        else
        {
            //      Log::info("Found '" . $line . "'");
            return $loader->get($line, $default);
        }
    }

    /**
     * Get the package namespace
     *
     * @return string
     */
    protected function namespacing()
    {
        // Get the config file loader
        $loader = App::make('config');

        // Get the package namespace or default to the root
        $namespace = $loader->get('esensi::core.namespace', 'esensi::');
        $line = str_singular($this->package) . '.namespace';

        // Use the packaged namespace
        if( $loader->has($namespace . $line) )
        {
            return $loader->get($namespace . $line);
        }

        // Use the global namespace or default to root namespace
        else
        {
            $loader->get($line, $namespace);
        }
    }

    /**
     * Get a language line
     *
     * @param string $key to language config line
     * @param array $replacements (optional) in language line
     * @return string
     */
    protected function language($key, array $replacements = [])
    {
        $namespace = $this->namespacing();
        $line = str_singular($this->package) . '.' .$key;
        $loader = App::make('translator');

        // Use local namespaced package
        if( $loader->has($namespace . $line) )
        {
            return $loader->get($namespace . $line, $replacements);
        }

        // Use global namespaced package
        elseif( $loader->has($line) )
        {
            return $loader->get($line, $replacements);
        }

        // Load the core as default
        else
        {
            // Load the language files because Laravel doesn't seem to have loaded them by now
            $loader->addNamespace('esensi', __DIR__ . '/../../../lang');
            return $loader->get('esensi::core.' . $key, $replacements);
        }
    }

    /**
     * Get an error language line
     *
     * @param string $key to language config line
     * @param array $replacements (optional) in language line
     * @return string
     */
    protected function error($key, array $replacements = [])
    {
        return $this->language('errors.' . $key, $replacements);
    }

    /**
     * Get a message language line
     *
     * @param string $key to language config line
     * @param array $replacements (optional) in language line
     * @return string
     */
    protected function message($key, array $replacements = [])
    {
        return $this->language('messages.' . $key, $replacements);
    }

    /**
     * Generate a redirect
     *
     * @param string $key to route config
     * @param array $params (optional) to construct route
     * @return \Illuminate\Routing\Redirector
     */
    protected function redirect($key, array $params = [])
    {
        // Redirect to intended route
        $route = $this->config('redirects.' . $this->ui . '.' . $key);
        return App::make('redirect')->route($route, $params);
    }

    /**
     * Generate a redirect back
     *
     * @param string $key to route config
     * @param array $params (optional) to construct route
     * @return \Illuminate\Routing\Redirector
     */
    protected function back($key, array $params = [])
    {
        // Short circuit to referrer URL or follow redirect
        $referer = App::make('request')->header('referer');
        $redirect = !empty($referer) ? App::make('redirect')->back() : $this->redirect($key, $params);
        return $redirect;
    }

}
