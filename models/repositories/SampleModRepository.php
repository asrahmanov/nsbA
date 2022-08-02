<?php

namespace app\models\repositories;

use app\engine\Db;
use app\models\entities\SampleMod;
use app\models\Repository;

class SampleModRepository extends Repository
{

    public function getTableName()
    {
        return 'nbs_sample_mods';
    }

    public function getIdName()
    {
        return 'id';
    }

    public function getEntityClass()
    {
        return SampleMod::class;
    }

    public function getFreshMods()
    {
        $sql = " SELECT * FROM nbs_sample_mods ORDER BY id DESC LIMIT 100";
        return Db::getInstance()->queryAll($sql);
    }

}
