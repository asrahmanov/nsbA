<?php


namespace app\models\repositories;

use app\engine\App;
use app\models\entities\SiteTypes;

use app\models\Repository;
use app\engine\Db;

class SiteTypesRepository extends Repository
{

    public function getTableName()
    {
        return 'fr_site_types';
    }

    public function getIdName()
    {
        return 'site_type_id';
    }


    public function getEntityClass()
    {
        return SiteTypes::class;
    }





}
