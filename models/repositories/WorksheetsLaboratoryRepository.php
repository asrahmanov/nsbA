<?php

namespace app\models\repositories;

use app\engine\App;
use app\models\entities\Mail;
use app\models\entities\WorksheetsLaboratory;

use app\models\Repository;
use app\engine\Db;

class WorksheetsLaboratoryRepository extends Repository
{
    public function getEntityClass()
    {
        return WorksheetsLaboratory::class;
    }

    public function getIdName()
    {
        return 'id';
    }

    public function getTableName()
    {
        return 'nbs_worksheets_laboratory';
    }


    public function getAll()
    {
        $tableName = $this->getTableName();
        $sql = "SELECT * FROM nbs_worksheets_laboratory
        INNER JOIN fr_main_table ON fr_main_table.proj_id = nbs_worksheets_laboratory.proj_id
        WHERE fr_main_table.status_client NOT IN (27, 28, 29, 33)
        ";
        return Db::getInstance()->queryAll($sql);
    }

    public function getByProj($proj_id)
    {

        $sql = "SELECT *, nbs_worksheets_laboratory.id  as id, nbs_worksheets_laboratory.comments  as comments,
        nbs_worksheets_laboratory.created_at  as created_at
        FROM nbs_worksheets_laboratory
        INNER JOIN fr_main_table ON fr_main_table.proj_id = nbs_worksheets_laboratory.proj_id
        INNER JOIN nbs_users ON nbs_users.id = nbs_worksheets_laboratory.user_id
        WHERE nbs_worksheets_laboratory.proj_id =:proj_id
        ";
        $params = [
            'proj_id' => $proj_id
        ];
        return Db::getInstance()->queryOne($sql, $params);
    }

    public function deleteByProjId ($proj_id)
    {
        $sql = "
            UPDATE nbs_worksheets_laboratory
            SET deleted = 1
            WHERE proj_id =:proj_id
        ";
        $params = [
            'proj_id' => $proj_id
        ];
        return Db::getInstance()->execute($sql, $params);
    }


    public function checkByProjId ($proj_id)
    {
        $sql = "
            SELECT id FROM nbs_worksheets_laboratory
            WHERE proj_id =:proj_id
        ";
        $params = [
            'proj_id' => $proj_id
        ];
        return Db::getInstance()->queryOne($sql, $params);
    }

}
