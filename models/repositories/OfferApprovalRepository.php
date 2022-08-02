<?php


namespace app\models\repositories;

use app\engine\Db;
use app\models\entities\OfferApproval;
use app\models\Repository;

class OfferApprovalRepository extends Repository
{

    public function getTableName()
    {
        return 'nbs_offer_approval';
    }

    public function getIdName()
    {
        return 'id';
    }

    public function getEntityClass()
    {
        return OfferApproval::class;
    }

    public function getApproval($user_id)
    {
        $sql = "
        SELECT DISTINCT
            noa.offer_id, fmt.internal_id 
        FROM
            nbs_offer_approval noa
            JOIN fr_main_table fmt ON noa.offer_id = fmt.proj_id
        WHERE
            (noa.approved = 0 OR isnull(noa.approved))    
            AND noa.user_id = $user_id
        ";
        return  Db::getInstance()->queryAll($sql);
    }


}
