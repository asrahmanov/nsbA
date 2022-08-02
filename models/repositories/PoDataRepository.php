<?php


namespace app\models\repositories;

use app\models\entities\PoData;
use app\models\Repository;


class PoDataRepository extends Repository
{

    public function getTableName()
    {
        return 'po_data';
    }

    public function getEntityClass()
    {
        return PoData::class;
    }

    public function getIdName()
    {
        return 'id';
    }

}
