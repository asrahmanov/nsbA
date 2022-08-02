<?php

namespace app\models\repositories;

use app\engine\App;
use app\models\entities\DiseaseCategory;
use app\models\Repository;
use app\engine\Db;

class DiseaseCategoryRepository extends Repository
{

    public function getTableName()
    {
        return 'nbs_disease_category';
    }

    public function getIdName()
    {
        return 'id';
    }

    public function getEntityClass()
    {
        return DiseaseCategory::class;
    }

}
