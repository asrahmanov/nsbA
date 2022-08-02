<?php


namespace app\models\repositories;

use app\models\entities\OfferItemClinicalInformation;
use app\models\Repository;

class OfferItemClinicalInformationRepository extends Repository
{

    public function getTableName()
    {
        return 'nbs_offer_item_clinical_information';
    }

    public function getIdName()
    {
        return 'id';
    }

    public function getEntityClass()
    {
        return OfferItemClinicalInformation::class;
    }

}
