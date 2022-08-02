<?php


namespace app\models\repositories;

use app\engine\App;
use app\models\entities\Container;
use app\models\Repository;
use app\engine\Db;


class ContainerRepository extends Repository
{

    public function getTableName()
    {
        return 'nbs_container';
    }

    public function getEntityClass()
    {
        return Container::class;
    }

    public function getIdName()
    {
        return 'id';
    }

}
