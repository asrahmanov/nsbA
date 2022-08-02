<?php


namespace app\models\repositories;

use app\models\entities\OfferApeItemExclusionCriteria;
use app\models\Repository;

class OfferApeItemExclusionCriteriaRepository extends Repository
{

    public function getTableName()
    {
        return 'nbs_offer_ape_item_exclusion_criteria';
    }

    public function getIdName()
    {
        return 'id';
    }

    public function getEntityClass()
    {
        return OfferApeItemExclusionCriteria::class;
    }

}
