<?php


namespace app\models\repositories;

use app\models\entities\QuoteDoctor;


use app\models\Repository;
use app\engine\Db;

class QuoteDoctorRepository extends Repository
{

    public function getEntityClass()
    {
        return QuoteDoctor::class;
    }


    public function getIdName()
    {
        return 'id';
    }

    public function getTableName()
    {
        return 'nbs_quotes_doctors';
    }

}
