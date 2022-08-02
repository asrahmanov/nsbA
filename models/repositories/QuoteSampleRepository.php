<?php


namespace app\models\repositories;
use app\models\entities\QuoteSample;
use app\models\Repository;
use app\engine\Db;

class QuoteSampleRepository extends Repository
{

    public function getEntityClass()
    {
        return QuoteSample::class;
    }

    public function getIdName()
    {
        return 'id';
    }

    public function getTableName()
    {
        return 'nbs_quotes_samples';
    }

}
