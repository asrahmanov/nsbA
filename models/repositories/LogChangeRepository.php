<?php

namespace app\models\repositories;

use app\models\entities\LogChange;
use app\models\Repository;


class LogChangeRepository extends Repository
{

    public function getTableName ()
    {
        return 'nbs_log_change';
    }

    public function getIdName ()
    {
        return 'id';
    }

    public function getEntityClass ()
    {
        return  LogChange::class;
    }

}
