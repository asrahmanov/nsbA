<?php

namespace app\models\repositories;

use app\engine\App;
use app\models\entities\–°urrency;
use app\models\Repository;
use app\engine\Db;


class –°urrencyRepository extends Repository
{

    public function getTableName()
    {
        return 'fr_currency';
    }

    public function getEntityClass()
    {
        return –°urrency::class;
    }

    public function getIdName()
    {
        return 'currency_id';
    }



}
