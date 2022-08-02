<?php
namespace app\models\repositories;

use app\engine\App;
use app\models\entities\Frstatus;
use app\models\Repository;
use app\engine\Db;


class FrstatusRepository extends Repository
{

    public function getTableName()
    {
        return 'fr_status_values';
    }

    public function getIdName()
    {
        return 'fr_status_id';
    }


    public function getEntityClass()
    {
        return Frstatus::class;
    }







}
