<?php


namespace app\models\repositories;

use app\engine\App;
use app\models\entities\Pages;
use app\models\Repository;
use app\engine\Db;

class PagesRepository extends Repository
{

    public function getTableName()
    {
        return 'nbs_pages';
    }

    public function getIdName()
    {
        return 'id';
    }

    public function getEntityClass()
    {
        return Pages::class;
    }




}
