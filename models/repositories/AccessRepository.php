<?php


namespace app\models\repositories;

use app\engine\App;
use app\models\entities\Access;
use app\models\Repository;
use app\engine\Db;

class AccessRepository extends Repository
{

    public function getTableName()
    {
        return 'nbs_access';
    }

    public function getIdName()
    {
        return 'id';
    }

    public function getEntityClass()
    {
        return Access::class;
    }


    // Проверка разрешина ли данная страница для  пользователя
    public function checkAccess($template, $role_id)
    {
        $tableName = $this->getTableName();
        $sql = "SELECT COUNT(nbs_access.id) as access FROM nbs_access 
        INNER JOIN nbs_pages ON  nbs_pages.id = nbs_access.page_id
        WHERE nbs_access.role_id=:role_id 
        AND nbs_pages.template=:template
        
        ";
        $params = [
            'role_id' => $role_id,
            'template' => $template
        ];

         $result = Db::getInstance()->queryOne($sql, $params);
         return $result['access'];


    }


    public function getId($role_id, $page_id)
    {
        $tableName = $this->getTableName();
        $sql = "SELECT id FROM {$tableName} 
            WHERE role_id=:role_id 
            AND page_id=:page_id LIMIT 1";
        $params = [
            'role_id' => $role_id,
            'page_id' => $page_id
        ];
        return Db::getInstance()->queryOne($sql, $params)['id'];
    }

}
