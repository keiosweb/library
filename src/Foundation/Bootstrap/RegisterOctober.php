<?php namespace October\Rain\Foundation\Bootstrap;

use Illuminate\Http\Request;
use October\Rain\Support\ClassLoader;
use Illuminate\Contracts\Foundation\Application;

class RegisterOctober
{

    /**
     * Specific features for OctoberCMS.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @return void
     */
    public function bootstrap(Application $app)
    {
        /*
         * Register singletons
         */
        $app->singleton('string', function () {
            return new \October\Rain\Support\Str;
        });

        /*
         * Change paths based on config
         */
        if ($pluginsPath = $app['config']->get('cms.pluginsPathLocal')) {
            $app->setPluginsPath($pluginsPath);
        }

        if ($themesPath = $app['config']->get('cms.themesPathLocal')) {
            $app->setThemesPath($themesPath);
        }

        /*
         * Set execution context
         */
        $requestPath = $this->normalizeUrl($app['request']->path());
        $backendUri = $this->normalizeUrl($app['config']->get('cms.backendUri', 'backend'));
        if (starts_with($requestPath, $backendUri)) {
            $app->setExecutionContext('back-end');
        }
        else {
            $app->setExecutionContext('front-end');
        }
    }

    /**
     * Adds leading slash from a URL.
     *
     * @param string $url URL to normalize.
     * @return string Returns normalized URL.
     */
    protected function normalizeUrl($url)
    {
        if (substr($url, 0, 1) != '/')
            $url = '/'.$url;

        if (!strlen($url))
            $url = '/';

        return $url;
    }

}