<?php


namespace app\models\repositories;

use app\models\entities\OfferRuItemInclusionCriteria;
use app\models\Repository;

class OfferRuItemInclusionCriteriaRepository extends Repository
{

    public function getTableName()
    {
        return 'nbs_offer_ru_item_inclusion_criteria';
    }

    public function getIdName()
    {
        return 'id';
    }

    public function getEntityClass()
    {
        return OfferRuItemInclusionCriteria::class;
    }

}
