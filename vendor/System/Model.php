<?php


namespace System;


abstract class Model
{
    /**
     * Application Object
     * @var \System\Appliaction
     */
    protected $app;
    /**
     * Table name
     * @var string
     */
    protected $table;


    /**
     * Model constructor.
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

    /**
     * Call database methods dynamically
     * @param string $method
     * @param array $args
     * @return mixed
     */
    public function __call($method, $args)
    {
        return call_user_func_array([$this->app->db, $method], $args);
    }

    /**
     * Get All rows
     * @return array
     */
    public function all()
    {
        return $this->fetchAll($this->table);
    }

    /**
     * Get row by id
     * @param int $id
     * @return \stdClass | null
     */
    public function get($id)
    {
        return $this->where('id = ? ', $id)->fetch($this->table);
    }
}