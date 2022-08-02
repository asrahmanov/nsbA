<?php


namespace app\models\repositories;

use app\engine\App;
use app\models\entities\Role;

use app\models\Repository;
use app\engine\Db;

class RoleRepository extends Repository
{

    public function getTableName()
    {
        return 'nbs_role';
    }

    public function getIdName()
    {
        return 'id';
    }


    public function getEntityClass()
    {
        return Role::class;
    }

    public function getAll()
    {
        $tableName = $this->getTableName();
        $sql = "SELECT * FROM {$tableName} WHERE id!=6";
        return Db::getInstance()->queryAll($sql);
    }

    public function getAllRoot()
    {
        $tableName = $this->getTableName();
        $sql = "SELECT * FROM {$tableName}";
        return Db::getInstance()->queryAll($sql);
    }



}
