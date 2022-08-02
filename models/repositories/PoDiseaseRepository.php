<?php

namespace app\models\repositories;

use app\models\entities\PoDisease;
use app\models\Repository;

class PoDiseaseRepository extends Repository
{

    public function getTableName()
    {
        return 'po_diseases';
    }

    public function getIdName()
    {
        return 'id';
    }

    public function getEntityClass()
    {
        return PoDisease::class;
    }

}
