<?php

namespace app\models\repositories;

use app\models\entities\PoDiseaseSampleMod;
use app\models\Repository;

class PoDiseaseSampleModRepository extends Repository
{

    public function getTableName()
    {
        return 'po_disease_sample_mod';
    }

    public function getIdName()
    {
        return 'id';
    }

    public function getEntityClass()
    {
        return PoDiseaseSampleMod::class;
    }

}
