<?php


namespace app\models\repositories;

use app\engine\App;
use app\models\entities\WorksheetsStatus;

use app\models\Repository;
use app\engine\Db;

class WorksheetsStatusRepository extends Repository
{

    public function getEntityClass()
    {
        return WorksheetsStatus::class;
    }


    public function getIdName()
    {
        return 'id';
    }

    public function getTableName()
    {
        return 'nbs_worksheets_status';
    }



}
