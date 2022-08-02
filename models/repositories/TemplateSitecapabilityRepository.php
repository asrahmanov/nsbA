<?php


namespace app\models\repositories;

use app\engine\App;
use app\models\entities\TemplateSitecapability;
use app\models\Repository;
use app\engine\Db;

class TemplateSitecapabilityRepository extends Repository
{

    public function getTableName()
    {
        return 'nbs_template_sitecapability';
    }

    public function getIdName()
    {
        return 'id';
    }

    public function getEntityClass()
    {
        return TemplateSitecapability::class;
    }


    public function getAllSorn()
    {

        $tableName = $this->getTableName();
        $sql = "SELECT * FROM {$tableName} ORDER by name ASC";
        return Db::getInstance()->queryAll($sql);

    }

}
