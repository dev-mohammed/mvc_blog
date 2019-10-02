<?php


namespace System\View;

use System\Application;

class ViewFactory
{
    /**
     * Application Object
     * @var \System\Application
     */
    private $app;

    /**
     * ViewFactory constructor.
     * @param \System\Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Render the given view path with the passed variable and generate new object
     * @param string $viewPath
     * @param array $data
     * @return \System\View\ViewInterface
     */
    public function render($viewPath, array $data = [])
    {
        return new View($this->app->file, $viewPath, $data);
    }


}