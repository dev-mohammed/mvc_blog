<?php


namespace System;


class Route
{
    /**
     * Application Object
     * @var \System\Application
     */
    private $app;

    /**
     * Routes Container
     * @var array
     */
    private $routes = [];

    /**
     * Not Found Url
     * @var string
     */
    private $notFound;

    /**
     * Route constructor.
     * @param \System\Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Add New Route
     * @param string $url
     * @param string $action
     * @param string $requestMethod
     * @return void
     */
    public function add($url, $action, $requestMethod = 'GET')
    {
        $route = [
            'url'     => $url,
            'pattern' => $this->generatePattern($url),
            'action'  => $this->getAction($action),
            'method'  => strtoupper($requestMethod)
        ];
        $this->routes[] = $route;
    }

    /**
     * Set Not Found Url
     * @param string $url
     * @return void
     */
    public function notFound($url)
    {
        $this->notFound = $url;
    }

    /**
     * Get Proper Route
     * @return array
     */
    public function getProperRoute()
    {
        foreach ($this->routes as $route) {
            if ($this->isMatching($route['pattern'])) {
                $arguments = $this->getArgumentsForm($route['pattern']);

                list($controller, $method) = explode('@', $route['action']);
                return [$controller, $method, $arguments];
            }
        }
    }


    /**
     * Generate a regex pattern for the given url
     * @param string $url
     * @return string
     */
    private function generatePattern($url)
    {
        $pattern = '#^';
        $pattern .= str_replace([':text', ':id'], ['([a-zA-Z0-9-]+)', '(\d+)'], $url);
        $pattern .= '$#';
        return $pattern;
    }

    /**
     * Get The proper Action
     * @param string $action
     * @return string
     */
    private function getAction($action)
    {
        $action = str_replace('/', '\\', $action);
        return strpos($action, '@') !== false ? $action : $action . '@index';
    }

    /**
     * Determine if the given pattern matches the current request url
     * @param string $pattern
     * @return bool
     */
    private function isMatching($pattern)
    {
        return preg_match($pattern, $this->app->request->url());
    }

    /**
     * Get Arguments from the current request url
     * based on the given pattern
     * @param string $pattern
     * @return array
     */
    private function getArgumentsForm($pattern)
    {
        preg_match($pattern, $this->app->request->url(), $matches);
        return array_shift($matches);
    }

}