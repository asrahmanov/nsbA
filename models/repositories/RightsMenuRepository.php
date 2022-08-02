<?php


namespace app\models\repositories;

use app\engine\App;
use app\models\entities\RightsMenu;

use app\models\Repository;
use app\engine\Db;

class RightsMenuRepository extends Repository
{

    public function getTableName()
    {
        return 'nbs_role_menu';
    }

    public function getEntityClass()
    {
        return RightsMenu::class;
    }

    public function getIdName()
    {
        return 'id';
    }

    public function getMenu() {
         $rights_id = App::call()->session->getSession('role_id');
         $tableName = $this->getTableName();
         $sql = "SELECT *  FROM {$tableName} WHERE role_id=:role_id ";
         $params = ['role_id' => $rights_id];
         $result =  Db::getInstance()->queryAll($sql, $params);
         return $result;
    }

}
