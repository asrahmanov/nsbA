<?php
namespace app\models\repositories;

use app\engine\App;

use app\models\entities\CompaniesContactsSpecial;
use app\models\Repository;
use app\engine\Db;


class CompaniesContactsSpecialRepository extends Repository
{

    public function getTableName ()
    {
        return 'nbs_companies_contacts_special';
    }

    public function getIdName ()
    {
        return 'id';
    }

    public function getEntityClass ()
    {
        return  CompaniesContactsSpecial::class;
    }

}
