<?php

namespace app\models\repositories;

use app\models\entities\IpAccess;
use app\models\Repository;


class IpAccessRepository extends Repository
{

    public function getTableName()
    {
        return 'nbs_ip_access';
    }

    public function getEntityClass()
    {
        return IpAccess::class;
    }

    public function getIdName()
    {
        return 'id';
    }



}
