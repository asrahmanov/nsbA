<?php


namespace app\models\repositories;

use app\models\entities\OfferApeItemAssociatedInformation;
use app\models\Repository;

class OfferApeItemAssociatedInformationRepository extends Repository
{

    public function getTableName()
    {
        return 'nbs_offer_ape_item_associated_information';
    }

    public function getIdName()
    {
        return 'id';
    }

    public function getEntityClass()
    {
        return OfferApeItemAssociatedInformation::class;
    }

}
