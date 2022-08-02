<?php


namespace app\models\repositories;

use app\models\entities\OfferRuItemTimeline;
use app\models\Repository;

class OfferRuItemTimelineRepository extends Repository
{

    public function getTableName()
    {
        return 'nbs_offer_ru_item_timeline';
    }

    public function getIdName()
    {
        return 'id';
    }

    public function getEntityClass()
    {
        return OfferRuItemTimeline::class;
    }

}
