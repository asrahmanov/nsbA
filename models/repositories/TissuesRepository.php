<?php


namespace app\models\repositories;

use app\engine\App;
use app\models\entities\Tissues;
use app\models\Repository;
use app\engine\Db;

class TissuesRepository extends Repository
{

    public function getTableName()
    {
        return 'nbs_tissues';
    }

    public function getIdName()
    {
        return 'id';
    }

    public function getEntityClass()
    {
        return Tissues::class;
    }

    public function getLike($text)
    {
        $tableName = $this->getTableName();
        $sql = "SELECT * FROM {$tableName} WHERE name like  '%$text%'";
        return Db::getInstance()->queryAll($sql);
    }


}
