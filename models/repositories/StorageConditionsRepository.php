<?php


namespace app\models\repositories;

use app\models\entities\StorageConditions;
use app\models\Repository;

class StorageConditionsRepository extends Repository
{

    public function getTableName()
    {
        return 'nbs_storage_conditions';
    }

    public function getIdName()
    {
        return 'id';
    }

    public function getEntityClass()
    {
        return StorageConditions::class;
    }

}
