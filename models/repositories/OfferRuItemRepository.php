<?php


namespace app\models\repositories;

use app\models\entities\OfferRuItem;
use app\models\Repository;

class OfferRuItemRepository extends Repository
{

    public function getTableName()
    {
        return 'nbs_offer_ru_item';
    }

    public function getIdName()
    {
        return 'id';
    }

    public function getEntityClass()
    {
        return OfferRuItem::class;
    }

}
