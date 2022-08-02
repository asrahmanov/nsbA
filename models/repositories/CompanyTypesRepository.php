<?php
namespace app\models\repositories;

use app\engine\App;

use app\models\entities\CompanyTypes;
use app\models\Repository;
use app\engine\Db;


class CompanyTypesRepository extends Repository
{

    public function getTableName()
    {
        return 'nbs_company_types';
    }

    public function getIdName()
    {
        return 'id';
    }


    public function getEntityClass()
    {
        return  CompanyTypes::class;
    }

}
