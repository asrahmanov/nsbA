<?php


namespace app\models\repositories;

use app\engine\App;
use app\models\entities\TissuesSitecapability;
use app\models\Repository;
use app\engine\Db;

class TissuesSitecapabilityRepository extends Repository
{

    public function getTableName()
    {
        return 'nbs_tissues_sitecapability';
    }

    public function getIdName()
    {
        return 'id';
    }

    public function getEntityClass()
    {
        return TissuesSitecapability::class;
    }


    public function getbyTepmlate($template_id)
    {
        $user_id = App::call()->session->getSession('user_id');
        $tableName = $this->getTableName();
        $sql = "SELECT * FROM {$tableName} 
        WHERE template_id=:template_id
        AND (users_group=:users_group OR users_group=:user_id)";
        $params = [
            'template_id' => $template_id,
            'users_group' => 'all',
            'user_id' => $user_id
        ];
        return Db::getInstance()->queryAll($sql, $params);
    }


    public function getbyTepmlateAdmin($template_id)
    {
        $tableName = $this->getTableName();
        $sql = "SELECT * FROM {$tableName} WHERE 
        template_id=:template_id
        AND users_group=:users_group
        ";
        $params = [
            'template_id' => $template_id,
            'users_group' => 'all'
        ];
        return Db::getInstance()->queryAll($sql, $params);
    }


    public function getAllTissues()
    {
        $tableName = $this->getTableName();
        $sql = "SELECT  *, nbs_tissues_sitecapability.id as id FROM {$tableName}
        INNER JOIN nbs_template_sitecapability ON nbs_template_sitecapability.id = nbs_tissues_sitecapability.template_id
        ORDER by tissues ASC";
        return Db::getInstance()->queryAll($sql);
    }

    public function getAllTissuesByTemplate($template_id)
    {

        $tableName = $this->getTableName();
        $sql = "SELECT  * ,nbs_tissues_sitecapability.id as id  FROM {$tableName}
        INNER JOIN nbs_template_sitecapability ON nbs_template_sitecapability.id = nbs_tissues_sitecapability.template_id
        WHERE nbs_tissues_sitecapability.template_id=:template_id
        ORDER by tissues ASC";
        $params = [
            'template_id' => $template_id
        ];
        return Db::getInstance()->queryAll($sql, $params);
    }


}
