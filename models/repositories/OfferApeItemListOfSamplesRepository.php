<?php


namespace app\models\repositories;

use app\models\entities\OfferApeItemListOfSamples;
use app\models\Repository;

class OfferApeItemListOfSamplesRepository extends Repository
{

    public function getTableName()
    {
        return 'nbs_offer_ape_item_list_of_samples';
    }

    public function getIdName()
    {
        return 'id';
    }

    public function getEntityClass()
    {
        return OfferApeItemListOfSamples::class;
    }

}
