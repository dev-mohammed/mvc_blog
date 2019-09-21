<?php

namespace System;
class Application
{
    /**
     * Container
     * @var array
     */
    private $container = [];

    /**
     * Application constructor.
     * @param \System\File $file
     */
    public function __construct(File $file)
    {
        $this->share('file', $file);
        $this->registerClasses();
        $this->loadHelpers();
    }

    /**
     * Register classes in spl auto load register
     * @return void
     */
    private function registerClasses()
    {
        spl_autoload_register([$this, 'load']);
    }

    /**
     * Load Class through autoLoading
     * @param string $class
     * @return void
     */
    public function load($class)
    {
        if (strpos($class, 'App') === 0) {
            $file = $this->file->to($class . '.php');
        } else {
            // get the class from vendor
            $file = $this->file->toVendor($class . '.php');
        }
        if ($this->file->exists($file)) {
            $this->file->require($file);
        }
    }

    /**
     * Get Shared Value
     * @param string $key
     * @return mixed|null
     */
    public function get($key)
    {
        return isset($this->container[$key]) ? $this->container[$key] : null;
    }

    /**
     * Share the given Key|value Through Application
     *
     * @param string $key
     * @param mixed $value
     * $return mixed
     */
    public function share($key, $value)
    {
        $this->container[$key] = $value;
    }

    /**
     * Get shared value dynamically
     * @param string $key
     * @return mixed|null
     */
    public function __get($key)
    {
        return $this->get($key);
    }

    /**
     * Load Helpers File
     * @return void
     */
    private function loadHelpers()
    {
        $this->file->require($this->file->toVendor('helpers.php'));
    }
}