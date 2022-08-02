<?php


namespace app\models\repositories;

use app\models\entities\SiteLogWorkStatuses;

use app\models\Repository;

class SiteLogWorkStatusesRepository extends Repository
{

    public function getTableName()
    {
        return 'nbs_site_log_work_status';
    }

    public function getIdName()
    {
        return 'id';
    }


    public function getEntityClass()
    {
        return SiteLogWorkStatuses::class;
    }





}
