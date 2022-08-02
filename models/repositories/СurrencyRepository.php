<?php

namespace app\models\repositories;

use app\engine\App;
use app\models\entities\Сurrency;
use app\models\Repository;
use app\engine\Db;


class СurrencyRepository extends Repository
{

    public function getTableName()
    {
        return 'fr_currency';
    }

    public function getEntityClass()
    {
        return Сurrency::class;
    }

    public function getIdName()
    {
        return 'currency_id';
    }



}
