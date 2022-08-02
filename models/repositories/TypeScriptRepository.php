<?php


namespace app\models\repositories;

use app\engine\App;

use app\models\entities\TypeScript;
use app\models\Repository;
use app\engine\Db;

class TypeScriptRepository extends Repository
{

    public function getTableName()
    {
        return 'nbs_scripts_type';
    }

    public function getIdName()
    {
        return 'id';
    }


    public function getEntityClass()
    {
        return TypeScript::class;
    }



}
