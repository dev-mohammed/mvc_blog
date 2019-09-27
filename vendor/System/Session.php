<?php

namespace System;


class Session
{

    /**
     * Application Object
     * @var \System\Application
     */
    private $app;

    /**
     * Session constructor.
     * @param \System\Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function set($key, $value)
    {
        echo $key . ' => ' . $value;
    }

}