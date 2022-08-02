<?php


namespace app\models\repositories;

use app\models\entities\OfferRuItemListOfSamples;
use app\models\Repository;

class OfferRuItemListOfSamplesRepository extends Repository
{

    public function getTableName()
    {
        return 'nbs_offer_ru_item_list_of_samples';
    }

    public function getIdName()
    {
        return 'id';
    }

    public function getEntityClass()
    {
        return OfferRuItemListOfSamples::class;
    }

}
