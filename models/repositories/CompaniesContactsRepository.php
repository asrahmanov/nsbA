<?php
namespace app\models\repositories;

use app\engine\App;

use app\models\entities\CompaniesContacts;
use app\models\Repository;
use app\engine\Db;


class CompaniesContactsRepository extends Repository
{

    public function getTableName ()
    {
        return 'nbs_companies_contacts';
    }

    public function getIdName ()
    {
        return 'id';
    }

    public function getEntityClass ()
    {
        return  CompaniesContacts::class;
    }

    public function getSiteContactsBySiteId ($site_id) {
        $sql = "
            SELECT *
            FROM nbs_companies_contacts
            WHERE company_id = :site_id";
        $params = ['site_id' => $site_id];
        return Db::getInstance()->queryAll($sql, $params);
    }


}
