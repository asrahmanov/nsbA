<?php

namespace app\models\repositories;

use app\models\entities\LabLevels;
use app\models\Repository;


class LabLevelsRepository extends Repository
{

    public function getTableName ()
    {
        return 'nbs_lab_levels';
    }

    public function getIdName ()
    {
        return 'id';
    }

    public function getEntityClass ()
    {
        return  LabLevels::class;
    }

}
