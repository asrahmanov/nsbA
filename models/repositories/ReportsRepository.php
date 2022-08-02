<?php

namespace app\models\repositories;

use app\engine\App;


use app\models\Repository;
use app\engine\Db;

class ReportsRepository extends Repository
{

    public function getEntityClass()
    {
        return Worksheets::class;
    }

    public function getIdName()
    {
        return 'id';
    }

    public function getTableName()
    {
        return 'nbs_worksheets';
    }

    public function getJoinOrders ($date_from = '', $date_to = '')
    {
        if ($date_from !== '')
            $from_cond = "AND DATE(nbs_worksheets.created_at) >= '$date_from'";
        else
            $from_cond = '';
        if ($date_to !== '')
            $to_cond = "AND DATE(nbs_worksheets.created_at) <= '$date_to'";
        else
            $to_cond = '';
        $sql = "SELECT 
        nbs_users.id, 
        CONCAT (nbs_users.firstname, ' ', nbs_users.lasttname) as fio, 
        SUM(IF(nbs_worksheets.status_id = 1, 1, 0)) as new,
        SUM(IF(nbs_worksheets.status_id = 2, 1, 0)) as ok,
        SUM(IF(nbs_worksheets.status_id = 3, 1, 0)) as otkaz,
        COUNT(nbs_worksheets.status_id) as vsego
        FROM nbs_users 
        INNER JOIN nbs_worksheets ON nbs_users.id = nbs_worksheets.user_id
        WHERE nbs_users.deleted != 1
        AND nbs_users.id NOT IN (37, 49, 59,  63, 13, 17, 13, 30)
        $from_cond $to_cond 
        GROUP by nbs_users.id
        ORDER by new ASC";
//        var_dump($sql);
        return  Db::getInstance()->queryAll($sql);
    }

}
