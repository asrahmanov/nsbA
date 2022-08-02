<?php


namespace app\models\repositories;

use app\engine\App;
use app\models\entities\SiteCapabilityType;
use app\models\Repository;
use app\engine\Db;

class SiteCapabilityTypeRepository extends Repository
{

    public function getTableName()
    {
        return 'nbs_site_capability_type';
    }

    public function getIdName()
    {
        return 'id';
    }

    public function getEntityClass()
    {
        return SiteCapabilityType::class;
    }




}
