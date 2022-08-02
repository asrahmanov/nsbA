<?php


namespace app\models\repositories;

use app\engine\App;
use app\models\entities\Company;
use app\models\Repository;
use app\engine\Db;


class CompanyRepository extends Repository
{

    public function getTableName()
    {
        return 'fr_scripts';
    }

    public function getEntityClass()
    {
        return Company::class;
    }


    public function getIdName()
    {
        return 'script_id';
    }


    public function getCompanyOne($script_id)
    {
        $tableName = $this->getTableName();
        $idName = $this->getIdName();
        $sql = "SELECT * FROM {$tableName} WHERE  $idName=:$idName ";
        $params = [$idName => $script_id];
        $result = Db::getInstance()->queryOne($sql,$params);
        return $result;
    }


    public function getAllSort()
    {
        $tableName = $this->getTableName();
        $sql = "SELECT * FROM {$tableName} ORDER by company_name ASC ";
        $result = Db::getInstance()->queryAll($sql);
        return $result;
    }


    public function getCompanyPriority() {
        $tableName = $this->getTableName();
        $sql = "SELECT * FROM {$tableName} 
        WHERE priority > 0
        ORDER by company_name ASC ";
        $result = Db::getInstance()->queryAll($sql);
        return $result;
    }

    public function getAll() {
        $sql = "SELECT * FROM fr_scripts fs
        INNER JOIN nbs_scripts_type nst ON nst.id = fs.script_type
        WHERE fs.deleted != 1
        ORDER BY fs.script";
        return Db::getInstance()->queryAll($sql);
    }

    public function getAllApi() {
        $sql = "SELECT * FROM fr_scripts";
        return Db::getInstance()->queryAll($sql);
    }






}
