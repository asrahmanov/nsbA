<?php

namespace app\models\repositories;

use app\engine\App;
use app\models\entities\Mail;
use app\models\entities\WorksheetsInvertory;

use app\models\Repository;
use app\engine\Db;

class WorksheetsInvertoryRepository extends Repository
{
    public function getEntityClass()
    {
        return WorksheetsInvertory::class;
    }

    public function getIdName()
    {
        return 'id';
    }

    public function getTableName()
    {
        return 'nbs_worksheets_invertory';
    }


    public function getAll()
    {
        $tableName = $this->getTableName();
        $sql = "SELECT * FROM nbs_worksheets_invertory
        INNER JOIN fr_main_table ON fr_main_table.proj_id = nbs_worksheets_invertory.proj_id
        WHERE fr_main_table.status_client -- NOT IN (27, 28, 29, 33)te
        AND fr_main_table.fr_date >= '2022-01-01'
        ORDER by fr_main_table.fr_date DESC
        ";
        return Db::getInstance()->queryAll($sql);
    }

    public function getByProj($proj_id)
    {

        $sql = "SELECT *, nbs_worksheets_invertory.id  as id, nbs_worksheets_invertory.comments  as comments,
        nbs_worksheets_invertory.created_at  as created_at
        FROM nbs_worksheets_invertory
        INNER JOIN fr_main_table ON fr_main_table.proj_id = nbs_worksheets_invertory.proj_id
        INNER JOIN nbs_users ON nbs_users.id = nbs_worksheets_invertory.user_id
        WHERE nbs_worksheets_invertory.proj_id =:proj_id
        ";
        $params = [
            'proj_id' => $proj_id
        ];
        return Db::getInstance()->queryOne($sql, $params);
    }

    public function deleteByProjId ($proj_id)
    {
        $sql = "
            UPDATE nbs_worksheets_invertory
            SET deleted = 1
            WHERE proj_id =:proj_id
        ";
        $params = [
            'proj_id' => $proj_id
        ];
        return Db::getInstance()->execute($sql, $params);
    }
}
