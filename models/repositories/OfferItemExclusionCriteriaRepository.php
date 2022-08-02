<?php


namespace app\models\repositories;

use app\models\entities\OfferItemExclusionCriteria;
use app\models\Repository;

class OfferItemExclusionCriteriaRepository extends Repository
{

    public function getTableName()
    {
        return 'nbs_offer_item_exclusion_criteria';
    }

    public function getIdName()
    {
        return 'id';
    }

    public function getEntityClass()
    {
        return OfferItemExclusionCriteria::class;
    }

}
