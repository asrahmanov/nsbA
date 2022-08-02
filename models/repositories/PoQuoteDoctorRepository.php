<?php


namespace app\models\repositories;

use app\models\entities\PoQuoteDoctor;
use app\models\entities\QuoteDoctor;


use app\models\Repository;
use app\engine\Db;

class PoQuoteDoctorRepository extends Repository
{

    public function getEntityClass()
    {
        return PoQuoteDoctor::class;
    }


    public function getIdName()
    {
        return 'id';
    }

    public function getTableName()
    {
        return 'nbs_po_quotes_doctors';
    }

}
