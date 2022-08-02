<?php
namespace app\models\repositories;

use app\engine\App;

use app\models\entities\CompanyStaff;
use app\models\Repository;
use app\engine\Db;


class CompanyStaffRepository extends Repository
{

    public function getTableName()
    {
        return 'nbs_scripts_staff';
    }

    public function getIdName()
    {
        return 'id';
    }


    public function getEntityClass()
    {
        return  CompanyStaff::class;
    }


    public function getByCompany($script_id) {
        $tableName = $this->getTableName();
        $sql = "SELECT * FROM {$tableName} WHERE script_id=:script_id
        ORDER BY name ASC";
        $params = [
            'script_id' => $script_id
        ];
        return Db::getInstance()->queryAll($sql, $params);
    }




}
