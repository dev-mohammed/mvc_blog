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
     * Limit
     * @var int
     */
    private $limit;

    /**
     * Offset
     * @var int
     */
    private $offset;

    /**
     * Total Rows
     * @var int
     */
    private $rows = 0;

    /**
     * Wheres
     * @var array
     */
    private $wheres = [];

    /**
     * Selects
     * @var array
     */
    private $selects = [];

    /**
     * Joins
     * @var array
     */
    private $joins = [];

    /**
     * Order by
     * @var array
     */
    private $orderBy = [];


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
     * Set select clause
     *
     * @param string $select
     * @return $this
     */
    public function select($select)
    {
        $this->selects[] = $select;
        return $this;
    }

    /**
     * Set join clause
     *
     * @param string $join
     * @return $this
     */
    public function join($join)
    {
        $this->joins[] = $join;
        return $this;
    }

    /**
     * Set Limit and offset
     * @param int $limit
     * @param int $offset
     * @return $this
     */
    public function limit($limit, $offset = 0)
    {
        $this->limit  = $limit;
        $this->offset = $offset;
        return $this;
    }

    /**
     * Set Order by clause
     * @param string $column
     * @param string $sort
     * @return $this
     */
    public function orderBy($column, $sort = 'ASC')
    {
        $this->orderBy = [$column, $sort];
        return $this;
    }

    /**
     * Fetch Table
     * This will return only one record
     * @param string $table
     * @return \stdClass | null
     */
    public function fetch($table = null)
    {
        if ($table) {
            $this->table($table);
        }
        $sql    = $this->fetchStatement();
        $result = $this->query($sql, $this->bindings)->fetch();
        $this->reset();
        return $result;
    }


    /**
     * Fetch All Record from Table
     * @param string $table
     * @return array
     */
    public function fetchAll($table = null)
    {
        if ($table) {
            $this->table($table);
        }
        $sql        = $this->fetchStatement();
        $query      = $this->query($sql, $this->bindings);
        $results    = $query->fetchAll();
        $this->rows = $query->rowCount();
        $this->reset();
        return $results;
    }

    /**
     *Get total rows from last fetch all statement
     * @return int
     */
    public function rows()
    {
        return $this->rows;
    }

    /**
     * Prepare Select Statement
     * @return string
     */
    private function fetchStatement()
    {
        $sql = 'SELECT ';
        if ($this->selects) {
            $sql .= implode(',', $this->selects);
        } else {
            $sql .= '*';
        }
        $sql .= ' FROM ' . $this->table . ' ';
        if ($this->joins) {
            $sql .= implode(' ', $this->joins);
        }
        if ($this->wheres) {
            $sql .= ' WHERE ' . implode(' ', $this->wheres) . ' ';
        }
        if ($this->limit) {
            $sql .= ' LIMIT ' . $this->limit;
        }
        if ($this->offset) {
            $sql .= ' OFFSET ' . $this->offset;
        }
        if ($this->orderBy) {
            $sql .= ' ORDER BY ' . implode(' ', $this->orderBy);
        }
        return $sql;
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
     * Delete Clause
     * @param string $table
     * @return $this
     */
    public function delete($table = null)
    {
        if ($table) {
            $this->table($table);
        }
        $sql = 'DELETE FROM ' . $this->table . ' ';
        if ($this->wheres) {
            $sql .= ' WHERE ' . implode(' ', $this->wheres);
        }
        $this->query($sql, $this->bindings);
        $this->reset();
        return $this;
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
        $this->reset();
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
            $sql .= ' WHERE ' . implode(' ', $this->wheres);
        }
        $this->query($sql, $this->bindings);
        $this->reset();
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

    /**
     * Reset All Data
     * @return void
     */
    private function reset()
    {
        $this->limit    = null;
        $this->offset   = null;
        $this->table    = null;
        $this->selects  = [];
        $this->joins    = [];
        $this->wheres   = [];
        $this->orderBy  = [];
        $this->data     = [];
        $this->bindings = [];
    }

}