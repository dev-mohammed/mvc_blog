<?php


namespace System;


class Loader
{
    /**
     * Application Object
     * @var \System\Appliaction
     */
    private $app;
    /**
     * Controllers Container
     * @var array
     */
    private $controllers = [];
    /**
     * Models Container
     * @var array
     */
    private $models = [];

    /**
     * Loader constructor.
     * @param \System\Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Call the given controller with the given method
     * and pass the given arguments to the controller method
     * @param string $controller
     * @param string $method
     * @param $arguments
     * @return mixed
     */
    public function action($controller, $method, $arguments)
    {
        $object = $this->controller($controller);
        return call_user_func([$object, $method], $arguments);
    }

    /**
     * Call the given controller
     * @param string $controller
     * @return object
     */
    public function controller($controller)
    {
        $controller = $this->getControllerName($controller);
        if (!$this->hasController($controller)) {
            $this->addController($controller);
        }
        return $this->getController($controller);
    }

    /**
     * Determine if the given class|controller exists
     * in the controllers container
     * @param string $controller
     * @return bool
     */
    private function hasController($controller)
    {
        return array_key_exists($controller, $this->controllers);
    }

    /**
     * Create new object for the given controller and store it
     * in controllers container
     * @param string $controller
     * @return void
     */
    private function addController($controller)
    {
        $object                         = new $controller($this->app);
        $this->controllers[$controller] = $object;
    }

    /**
     * Get the controller object
     * @param string $controller
     * @return object
     */
    private function getController($controller)
    {
        return $this->controllers[$controller];
    }

    /**
     * Get the full class name for the given controller
     * @param string $controller
     * @return string
     */
    private function getControllerName($controller)
    {
        $controller .= 'Controller';
        $controller = 'App\\Controllers\\' . $controller;
        return str_replace('/', '\\', $controller);
    }

    /**
     * Call the given model
     * @param string $model
     * @return mixed
     */
    public function model($model)
    {
        $model = $this->getModelName($model);
        if (!$this->hasModel($model)) {
            $this->addModel($model);
        }
        return $this->getModel($model);
    }

    /**
     * Get the full class name for the given model
     * @param string $model
     * @return string
     */
    private function getModelName($model)
    {
        $model .= 'Model';
        $model = 'App\\Models\\' . $model;
        return str_replace('/', '\\', $model);
    }

    /**
     * Determine if the given class|model exists
     * in the models container
     * @param string $model
     * @return bool
     */
    private function hasModel($model)
    {
        return array_key_exists($model, $this->models);
    }

    /**
     * Create new object for the given model and store it
     * in models container
     * @param string $model
     * @return void
     */
    private function addModel($model)
    {
        $object               = new $model($this->app);
        $this->models[$model] = $object;
    }

    /**
     * Get the model object
     * @param string $model
     * @return mixed
     */
    private function getModel($model)
    {
        return $this->models[$model];
    }


}