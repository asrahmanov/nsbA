<?php
namespace app\models\repositories;

use app\engine\App;
use app\models\entities\Courier;
use app\models\Model;
use app\models\Repository;
use app\engine\Db;

class CourierRepository extends Repository
{
    public function getTableName()
    {
        return 'nbs_courier';
    }

    public function getEntityClass()
    {
        return Courier::class;
    }

    public function getIdName()
    {
        return 'id';
    }

}
