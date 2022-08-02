<?php


namespace app\models\repositories;

use app\engine\App;
use app\models\entities\Departments;
use app\models\Repository;
use app\engine\Db;


class DepartmentsRepository extends Repository
{

    public function getTableName()
    {
        return 'nbs_department';
    }

    public function getEntityClass()
    {
        return Departments::class;
    }


    public function getIdName()
    {
        return 'id';
    }





}
