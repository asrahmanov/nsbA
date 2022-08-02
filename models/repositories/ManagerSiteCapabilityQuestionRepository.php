<?php


namespace app\models\repositories;

use app\engine\App;
use app\models\entities\ManagerSiteCapabilityQuestion;
use app\models\Repository;
use app\engine\Db;


class ManagerSiteCapabilityQuestionRepository extends Repository
{

    public function getTableName()
    {
        return 'nbs_manager_site_capability_questions';
    }

    public function getEntityClass()
    {
        return ManagerSiteCapabilityQuestion::class;
    }

    public function getIdName()
    {
        return 'id';
    }












}
