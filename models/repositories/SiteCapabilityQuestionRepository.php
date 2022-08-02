<?php


namespace app\models\repositories;

use app\engine\App;
use app\models\entities\SiteCapabilityQuestion;
use app\models\Repository;
use app\engine\Db;


class SiteCapabilityQuestionRepository extends Repository
{

    public function getTableName()
    {
        return 'nbs_site_capability_question';
    }

    public function getEntityClass()
    {
        return SiteCapabilityQuestion::class;
    }

    public function getIdName()
    {
        return 'id';
    }












}
