<?php

namespace app\models\repositories;

use app\engine\App;
use app\models\entities\PoStatus;
use app\models\Repository;
use app\engine\Db;


class PoStatusRepository extends Repository
{

    public function getTableName()
    {
        return 'nbs_po_status';
    }

    public function getEntityClass()
    {
        return PoStatus::class;
    }

    public function getIdName()
    {
        return 'status_id';
    }

}
