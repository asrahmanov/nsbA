<?php


namespace app\models\repositories;

use app\models\entities\OfferOption;
use app\models\Repository;

class OfferOptionRepository extends Repository
{

    public function getTableName()
    {
        return 'nbs_offer_options';
    }

    public function getIdName()
    {
        return 'id';
    }

    public function getEntityClass()
    {
        return OfferOption::class;
    }

}
