<?php

namespace app\models\repositories;

use app\engine\App;
use app\models\entities\DiseaseGroup;
use app\models\Repository;
use app\engine\Db;

class DiseaseGroupRepository extends Repository
{

    public function getTableName()
    {
        return 'nbs_disease_group';
    }

    public function getIdName()
    {
        return 'id';
    }

    public function getEntityClass()
    {
        return DiseaseGroup::class;
    }

}
