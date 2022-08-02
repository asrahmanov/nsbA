<?php


namespace app\models\repositories;

use app\models\entities\OfferRuItemExclusionCriteria;
use app\models\Repository;

class OfferRuItemExclusionCriteriaRepository extends Repository
{

    public function getTableName()
    {
        return 'nbs_offer_ru_item_exclusion_criteria';
    }

    public function getIdName()
    {
        return 'id';
    }

    public function getEntityClass()
    {
        return OfferRuItemExclusionCriteria::class;
    }

}
