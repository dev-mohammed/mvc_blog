<?php


namespace System;

use PDO;
use PDOException;


class Database
{
    /**
     * Application Object
     * @var \System\Appliaction
     */
    private $app;

    /**
     * PDO Connection
     * @var \PDO
     */
    private static $connection;
    /**
     * Table Name
     * @var string
     */
    private $table;
    /**
     * Data Container
     * @var array
     */
    private $data = [];

    /**
     * Bindings Container
     * @var array
     */
    private $bindings = [];
    /**
     * Last Insert Id
     * @var int
     */
    private $lastId;

    /**
     * Wheres
     * @var array
     */
    private $wheres = [];


    /**
     *  constructor.
     * @param \System\Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
        if (!$this->isConnected()) {
            $this->connect();
        }
    }


    /**
     * Determine if there is any connection to database
     * @return bool
     */
    private function isConnected()
    {
        return static::$connection instanceof PDO;
    }

    /**
     * Connect To Database
     * @return void
     */
    private function connect()
    {
        $connectionData = $this->app->file->call('config.php');
        extract($connectionData);
        try {
            static::$connection = new PDO('mysql:host=' . $server . ';dbname=' . $dbname, $dbuser, $dbpass);
            static::$connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
            static::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            static::$connection->exec('SET NAMES utf8');
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    /**
     * Get Database Connection Object PDO Object
     *
     * @return \PDO
     */
    public function connection()
    {
        return static::$connection;
    }

    /**
     * Set the table name
     * @param string $table
     * @return $this
     */
    public function table($table)
    {
        $this->table = $table;
        return $this;
    }

    /**
     * Set the table name
     * @param string $table
     * @return $this
     */
    public function from($table)
    {
        return $this->table($table);
    }

    /**
     * Set the data that will be stored in database table
     * @param mixed $key
     * @param mixed $value
     * @return $this
     */
    public function data($key, $value = null)
    {
        if (is_array($key)) {
            $this->data = array_merge($this->data, $key);
            $this->addToBindings($key);
        } else {
            $this->data[$key] = $value;
            $this->addToBindings($value);
        }
        return $this;
    }

    /**
     * Insert data to database
     * @param string $table
     * @return $this
     */
    public function insert($table = null)
    {
        if ($table) {
            $this->table($table);
        }
        $sql = 'INSERT INTO ' . $this->table . ' SET ';
        $sql .= $this->setFields();
        $this->query($sql, $this->bindings);
        $this->lastId = $this->connection()->lastInsertId();
        return $this;
    }

    /**
     * Update data In database
     * @param string $table
     * @return $this
     */
    public function update($table = null)
    {
        if ($table) {
            $this->table($table);
        }
        $sql = 'UPDATE ' . $this->table . ' SET ';
        $sql .= $this->setFields();
        if ($this->wheres) {
            $sql .= ' WHERE ' . implode('', $this->wheres);
        }
        $this->query($sql, $this->bindings);
        return $this;
    }


    /**
     * Set the fields for insert and update
     * @return string
     */
    private function setFields()
    {
        $sql = '';
        foreach (array_keys($this->data) as $key) {
            $sql .= '`' . $key . '` = ? , ';
        }
        return rtrim($sql, ', ');
    }

    public function where()
    {
        $bindings = func_get_args();
        $sql      = array_shift($bindings);
        $this->addToBindings($bindings);
        $this->wheres[] = $sql;
        return $this;
    }

    /**
     * Get the last insert id
     * @return int
     */
    public function lastId()
    {
        return $this->lastId;
    }

    /**
     * Add the given value to bindings
     * @param mixed $value
     * @return void
     */
    private function addToBindings($value)
    {
        if (is_array($value)) {
            $this->bindings = array_merge($this->bindings, array_values($value));
        } else {
            $this->bindings[] = $value;
        }
    }

    /**
     * Execute the given sql statement
     * @return \PDOStatement
     */
    public function query()
    {
        $bindings = func_get_args();
        $sql      = array_shift($bindings);
        if (count($bindings) == 1 AND is_array($bindings[0])) {
            $bindings = $bindings[0];
        }
        try {
            $query = $this->connection()->prepare($sql);
            foreach ($bindings as $key => $value) {
                $query->bindValue($key + 1, _e($value));
            }
            $query->execute();
            return $query;
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }
}