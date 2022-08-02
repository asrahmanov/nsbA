<?php


namespace app\models\repositories;

use app\models\entities\OfferItemInclusionCriteria;
use app\models\Repository;

class OfferItemInclusionCriteriaRepository extends Repository
{

    public function getTableName()
    {
        return 'nbs_offer_item_inclusion_criteria';
    }

    public function getIdName()
    {
        return 'id';
    }

    public function getEntityClass()
    {
        return OfferItemInclusionCriteria::class;
    }

}
