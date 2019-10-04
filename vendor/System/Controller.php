<?php


namespace System;


abstract class Controller
{
    /**
     * Application Object
     * @var \System\Appliaction
     */
    protected $app;

    /**
     * Controller constructor.
     * @param \System\Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Call shared application objects dynamically
     * @param string $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->app->get($key);
    }

}