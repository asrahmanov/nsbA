<?php


namespace app\models\repositories;

use app\engine\App;
use app\models\entities\OrdersStatus;
use app\models\Repository;
use app\engine\Db;


class OrdersStatusRepository extends Repository
{

    public function getTableName()
    {
        return 'nbs_orders_status';
    }

    public function getEntityClass()
    {
        return OrdersStatus::class;
    }

    public function getIdName()
    {
        return 'id';
    }

    public function getAll()
    {
        $tableName = $this->getTableName();
        $sql = "SELECT * FROM {$tableName} WHERE 
        deleted != 1";
        return Db::getInstance()->queryAll($sql);
    }


}
