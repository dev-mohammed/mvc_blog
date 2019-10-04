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
        } catch (PDOException $e) {
            die($e->getMessage());
        }
        echo $this->isConnected();
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


}