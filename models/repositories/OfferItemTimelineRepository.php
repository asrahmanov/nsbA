<?php


namespace app\models\repositories;

use app\models\entities\OfferItemTimeline;
use app\models\Repository;

class OfferItemTimelineRepository extends Repository
{

    public function getTableName()
    {
        return 'nbs_offer_item_timeline';
    }

    public function getIdName()
    {
        return 'id';
    }

    public function getEntityClass()
    {
        return OfferItemTimeline::class;
    }

}
