<?php


namespace app\models\repositories;

use app\models\entities\OfferApeItemTimeline;
use app\models\Repository;

class OfferApeItemTimelineRepository extends Repository
{

    public function getTableName()
    {
        return 'nbs_offer_ape_item_timeline';
    }

    public function getIdName()
    {
        return 'id';
    }

    public function getEntityClass()
    {
        return OfferApeItemTimeline::class;
    }

}
