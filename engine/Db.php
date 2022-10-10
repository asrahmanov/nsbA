<?php

namespace app\engine;


use app\traits\TSingletone;

class Db
{
    use TSingletone;


    private $config = [
        'driver' => 'mysql',
        'host' => 'localhost',
        'login' => 'admin',
        'password' => 'Arahmanov1488!',
        'database' => '_nbs',
        'charset' => 'utf8',
        'port' => '3306'
    ];


    private $connection = null;

    private function getConnection()
    {
        if (is_null($this->connection)) {
            $this->connection = new \PDO(
                $this->prepareDNSing(),
                $this->config['login'],
                $this->config['password']
            );

            $this->connection->setAttribute(\PDO::MYSQL_ATTR_INIT_COMMAND, "SET NAMES utf8");
            $this->connection->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
//            $this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $this->connection->exec("set names utf8");

        }
        return $this->connection;
    }


    private function query($sql, $params = [])
    {
        $pdoStatement = $this->getConnection()->prepare($sql);
        $pdoStatement->execute($params);
        return $pdoStatement;
    }

    public function lastInsertId()
    {
        return $this->connection->lastInsertId();
    }

    private function prepareDNSing()
    {
        return sprintf("%s:host=%s; dbname=%s; charset=%s",
            $this->config['driver'],
            $this->config['host'],
            $this->config['database'],
            $this->config['charset']

        );


    }

    public function execute($sql, $params)
    {
        $this->query($sql, $params);
        return true;
    }

    public function queryOne($sql, $params = [])
    {
        $result = $this->query($sql, $params)->fetchAll();
        if (count($result) == 0) {
            return 0;
        }
        return $result[0];
    }


    public function queryObject($sql, $params, $className)
    {
        $pdoStatement = $this->query($sql, $params);
        $pdoStatement->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, $className);
        return $pdoStatement->fetch();
    }


    public function queryAll($sql, $params = [])
    {
        return $this->query($sql, $params)->fetchAll();
    }

    public function __toString()
    {
        return "Db";
    }

}
