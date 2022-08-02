<?php

namespace app\models\repositories;

use app\engine\App;
use app\models\entities\Disease;
use app\models\Repository;
use app\engine\Db;

class DiseaseRepository extends Repository
{

    public function getTableName()
    {
        return 'nbs_disease';
    }

    public function getIdName()
    {
        return 'id';
    }

    public function getEntityClass()
    {
        return Disease::class;
    }



}
