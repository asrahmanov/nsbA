<?php


namespace app\models\repositories;

use app\engine\App;
use app\models\entities\Marketplace;
use app\models\Repository;
use app\engine\Db;


class MarketplaceRepository extends Repository
{

    public function getTableName()
    {
        return 'nbs_marketplace';
    }

    public function getEntityClass()
    {
        return Marketplace::class;
    }

    public function getIdName()
    {
        return 'id';
    }



}
