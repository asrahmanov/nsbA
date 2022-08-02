<?php


namespace app\models\repositories;

use app\engine\App;
use app\models\entities\MKB;
use app\models\Model;
use app\models\Repository;
use app\engine\Db;


class MKBRepository extends Repository
{

    public function getTableName()
    {
        return 'nbs_mkb';
    }

    public function getEntityClass()
    {
        return MKB::class;
    }

    public function getIdName()
    {
        return 'id';
    }


    public function getLike($text)
    {
        $tableName = $this->getTableName();
        $sql = "SELECT * FROM {$tableName} WHERE name like  '%$text%'";
        return Db::getInstance()->queryAll($sql);
    }


}
