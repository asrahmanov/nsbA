<?php


namespace app\models\repositories;

use app\engine\App;
use app\models\entities\Shipping;
use app\models\Model;
use app\models\Repository;
use app\engine\Db;


class ShippingRepository extends Repository
{

    public function getTableName()
    {
        return 'nbs_shipping';
    }

    public function getEntityClass()
    {
        return Shipping::class;
    }

    public function getIdName()
    {
        return 'id';
    }

    public function getAllbyId($id)
    {
        $tableName = $this->getTableName();
        $sql = "SELECT * FROM {$tableName} 
        WHERE courier_id= :courier_id
        ";
        $params = [
            'courier_id' => $id
        ];

        return Db::getInstance()->queryAll($sql, $params);
    }

}
