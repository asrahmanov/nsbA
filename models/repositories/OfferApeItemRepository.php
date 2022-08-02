<?php


namespace app\models\repositories;

use app\models\entities\OfferApeItem;
use app\models\Repository;

class OfferApeItemRepository extends Repository
{

    public function getTableName()
    {
        return 'nbs_offer_ape_item';
    }

    public function getIdName()
    {
        return 'id';
    }

    public function getEntityClass()
    {
        return OfferApeItem::class;
    }




}
