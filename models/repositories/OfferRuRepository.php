<?php


namespace app\models\repositories;

use app\engine\App;
use app\models\entities\OfferRu;
use app\models\Model;
use app\models\Repository;
use app\engine\Db;


class OfferRuRepository extends Repository
{

    public function getTableName()
    {
        return 'nbs_offer_ru';
    }

    public function getEntityClass()
    {
        return OfferRu::class;
    }

    public function getIdName()
    {
        return 'id';
    }

}
