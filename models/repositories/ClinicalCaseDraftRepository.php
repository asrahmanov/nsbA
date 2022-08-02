<?php


namespace app\models\repositories;

use app\engine\Db;
use app\models\entities\ClinicalCaseDraft;
use app\models\Repository;

class ClinicalCaseDraftRepository extends Repository
{

    public function getTableName()
    {
        return 'nbs_clinical_case_draft';
    }

    public function getIdName()
    {
        return 'id';
    }

    public function getEntityClass()
    {
        return ClinicalCaseDraft::class;
    }

    public function getAllDrafts($user_id)
    {
        $sql = "
            SELECT ncc.clinical_case_id, nccd.id
            FROM nbs_clinical_case_draft nccd
                JOIN nbs_clinical_case ncc ON nccd.clinical_case_id = ncc.id 
            WHERE nccd.user_id = $user_id
            ";
        return Db::getInstance()->queryAll($sql);
    }


    public function deleteDraftsByUserId($userId){
        $sql = "
            DELETE FROM nbs_clinical_case_draft 
            WHERE nbs_clinical_case_draft.user_id = :userId
            ";
        $params = [
            'userId' => $userId
        ];
        return Db::getInstance()->queryAll($sql, $params);

    }

}
