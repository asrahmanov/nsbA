<?php

namespace app\models;

use app\engine\Db;


abstract class Model
{
    public $arrayParams = [];

    public function __set($name, $value)
    {
        $this->$name = $value;
        $this->arrayParams[$name] = true;
    }

    public function __get($name)
    {
        return $this->$name;
    }


}

