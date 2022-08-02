<?php


namespace app\models\repositories;

use app\models\entities\OfferApeItemInclusionCriteria;
use app\models\Repository;

class OfferApeItemInclusionCriteriaRepository extends Repository
{

    public function getTableName()
    {
        return 'nbs_offer_ape_item_inclusion_criteria';
    }

    public function getIdName()
    {
        return 'id';
    }

    public function getEntityClass()
    {
        return OfferApeItemInclusionCriteria::class;
    }

}
