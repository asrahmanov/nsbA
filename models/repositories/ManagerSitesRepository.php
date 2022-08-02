<?php


namespace app\models\repositories;

use app\engine\App;
use app\models\entities\ManagerSites;

use app\models\Repository;
use app\engine\Db;

class ManagerSitesRepository extends Repository
{

    public function getEntityClass()
    {
        return ManagerSites::class;
    }


    public function getIdName()
    {
        return 'id';
    }

    public function getTableName()
    {
        return 'nbs_manager_sites';
    }


    public function getSiteManagerbyUser($user_id)
    {
        $sql = "SELECT *, fr_site_types.site_type as type_name FROM fr_sites
        INNER JOIN fr_site_types ON fr_site_types.site_type_id = fr_sites.site_type
        INNER JOIN fr_cities ON fr_cities.city_id = fr_sites.city
        INNER JOIN nbs_manager_sites ON nbs_manager_sites.site_id  = fr_sites.site_id
        WHERE nbs_manager_sites.user_id=:user_id";
        $params = ['user_id' => $user_id];
        return Db::getInstance()->queryAll($sql, $params);
    }


    public function getQuotableSitesByUser($user_id)
    {
        $sql = "SELECT * FROM fr_sites 
        INNER JOIN nbs_manager_sites ON nbs_manager_sites.site_id  = fr_sites.site_id
        WHERE nbs_manager_sites.user_id=:user_id
          AND quotable = 1";
        $params = ['user_id' => $user_id];
        return Db::getInstance()->queryAll($sql, $params);
    }

    public function getSiteManagerbyAll()
    {
        $sql = "SELECT * FROM fr_sites";
        return Db::getInstance()->queryAll($sql);
    }

    public function getSiteManagerbySites($site_id)
    {
        $sql = "SELECT *, nbs_manager_sites.id as id, nbs_users.id as id_user  FROM nbs_manager_sites 
        INNER JOIN nbs_users ON nbs_manager_sites.user_id  = nbs_users.id
        WHERE nbs_manager_sites.site_id=:site_id";
        $params = ['site_id' => $site_id];
        return Db::getInstance()->queryAll($sql, $params);
    }






}
