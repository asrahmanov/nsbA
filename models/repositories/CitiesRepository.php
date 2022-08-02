<?php


namespace app\models\repositories;

use app\engine\App;
use app\models\entities\Cities;

use app\models\Repository;
use app\engine\Db;

class CitiesRepository extends Repository
{

    public function getTableName()
    {
        return 'fr_cities';
    }

    public function getIdName()
    {
        return 'city_id';
    }


    public function getEntityClass()
    {
        return Cities::class;
    }


    public function getAllOrder()
    {
        $tableName = $this->getTableName();
        $sql = "SELECT * FROM {$tableName} ORDER by city_name ASC ";
        return Db::getInstance()->queryAll($sql);
    }


}
