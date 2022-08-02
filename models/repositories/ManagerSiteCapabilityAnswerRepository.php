<?php


namespace app\models\repositories;

use app\engine\App;
use app\models\entities\ManagerSiteCapabilityAnswer;

use app\models\Repository;
use app\engine\Db;

class ManagerSiteCapabilityAnswerRepository extends Repository
{

    public function getEntityClass()
    {
        return ManagerSiteCapabilityAnswer::class;
    }


    public function getIdName()
    {
        return 'id';
    }

    public function getTableName()
    {
        return 'nbs_manager_site_capability_answer';
    }


//    public function getByUsers($user_id) {
//        $sql = "SELECT * FROM fr_sites
//        INNER JOIN nbs_manager_sites ON nbs_manager_sites.site_id  = fr_sites.site_id
//        WHERE nbs_manager_sites.user_id=:user_id";
//        $params = ['user_id' => $user_id];
//        return Db::getInstance()->queryAll($sql, $params);
//    }
//
//    public function getSiteManagerbySites($site_id) {
//        $sql = "SELECT *, nbs_manager_sites.id as id  FROM nbs_manager_sites
//        INNER JOIN nbs_users ON nbs_manager_sites.user_id  = nbs_users.id
//        WHERE nbs_manager_sites.site_id=:site_id";
//        $params = ['site_id' => $site_id];
//        return Db::getInstance()->queryAll($sql, $params);
//    }





}
