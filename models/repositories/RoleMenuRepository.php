<?php


namespace app\models\repositories;

use app\engine\App;
use app\models\entities\RoleMenu;

use app\models\Repository;
use app\engine\Db;

class RoleMenuRepository extends Repository
{

    public function getTableName()
    {
        return 'nbs_role_menu';
    }

    public function getIdName()
    {
        return 'id';
    }

    public function getEntityClass()
    {
        return RoleMenu::class;
    }


    public function getId($role_id,$alias) {
        $tableName = $this->getTableName();
        $sql = "SELECT id FROM {$tableName} 
            WHERE role_id=:role_id 
            AND alias=:alias LIMIT 1";
        $params = [
            'role_id' => $role_id,
            'alias' => $alias
        ];
        return  Db::getInstance()->queryOne($sql, $params)['id'];
    }

}
