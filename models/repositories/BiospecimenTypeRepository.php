<?php


namespace app\models\repositories;

use app\models\entities\BiospecimenType;
use app\models\Repository;

class BiospecimenTypeRepository extends Repository
{

    public function getTableName()
    {
        return 'nbs_biospecimen_type';
    }

    public function getIdName()
    {
        return 'id';
    }

    public function getEntityClass()
    {
        return BiospecimenType::class;
    }

}
