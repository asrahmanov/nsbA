<?php


namespace app\models\repositories;

use app\engine\App;
use app\models\entities\Priority;
use app\models\Repository;
use app\engine\Db;


class PriorityRepository extends Repository
{

    public function getTableName()
    {
        return 'nbs_priority';
    }

    public function getEntityClass()
    {
        return Priority::class;
    }

    public function getIdName()
    {
        return 'id';
    }




}
