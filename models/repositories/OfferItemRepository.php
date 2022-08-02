<?php


namespace app\models\repositories;

use app\models\entities\OfferItem;
use app\models\Repository;

class OfferItemRepository extends Repository
{

    public function getTableName()
    {
        return 'nbs_offer_item';
    }

    public function getIdName()
    {
        return 'id';
    }

    public function getEntityClass()
    {
        return OfferItem::class;
    }




}
