<?php


namespace app\models\repositories;

use app\models\entities\OfferItemListOfSamples;
use app\models\Repository;

class OfferItemListOfSamplesRepository extends Repository
{

    public function getTableName()
    {
        return 'nbs_offer_item_list_of_samples';
    }

    public function getIdName()
    {
        return 'id';
    }

    public function getEntityClass()
    {
        return OfferItemListOfSamples::class;
    }

}
