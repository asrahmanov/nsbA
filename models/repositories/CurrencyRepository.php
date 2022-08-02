<?php


namespace app\models\repositories;

use app\engine\App;
use app\models\entities\Cities;

use app\models\entities\Currency;
use app\models\Repository;
use app\engine\Db;

class CurrencyRepository extends Repository
{

    public function getTableName()
    {
        return 'fr_currency';
    }

    public function getIdName()
    {
        return 'currency_id';
    }


    public function getEntityClass()
    {
        return Currency::class;
    }



}
