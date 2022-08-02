<?php


namespace app\models\repositories;

use app\models\entities\OfferRuItemClinicalInformation;
use app\models\Repository;

class OfferRuItemClinicalInformationRepository extends Repository
{

    public function getTableName()
    {
        return 'nbs_offer_ru_item_clinical_information';
    }

    public function getIdName()
    {
        return 'id';
    }

    public function getEntityClass()
    {
        return OfferRuItemClinicalInformation::class;
    }

}
