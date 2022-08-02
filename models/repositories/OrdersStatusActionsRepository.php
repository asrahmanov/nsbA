<?php


namespace app\models\repositories;

use app\engine\App;
use app\models\entities\OrdersStatusActions;
use app\models\Repository;
use app\engine\Db;


class OrdersStatusActionsRepository extends Repository
{

    public function getTableName()
    {
        return 'nbs_orders_status_action';
    }

    public function getEntityClass()
    {
        return OrdersStatusActions::class;
    }


    public function getIdName()
    {
        return 'id';
    }


    public function getStatusAllByProjectId($proj_id)
    {
        $tableName = $this->getTableName();
        $sql = "SELECT * FROM {$tableName}  WHERE proj_id=:proj_id
        ORDER BY created_at ASC";
        $params = [
            'proj_id' => $proj_id
        ];
        return Db::getInstance()->queryAll($sql, $params);
    }

    public function getChengeStatus()
    {
        $tableName = $this->getTableName();
        $date = DATE('Y-m-d');
        $sql = "SELECT DISTINCT(proj_id) FROM {$tableName} WHERE DATE(created_at) =:date
        ";
        $params = [
            'date' => $date
        ];
        return Db::getInstance()->queryAll($sql, $params);
    }

    public function getLog()
    {
        $tableName = $this->getTableName();
        $sql = "SELECT DISTINCT(proj_id), created_at as statusdate FROM {$tableName} ORDER by created_at  DESC";
        return Db::getInstance()->queryAll($sql);
    }


    public function getStatusDate($proj_id)
    {
        $tableName = $this->getTableName();
        $sql = "SELECT DISTINCT(proj_id), created_at as statusdate FROM {$tableName}
        WHERE proj_id=:proj_id
        ORDER by created_at  DESC 
        LIMIT 1";
        $params = [
            'proj_id' => $proj_id
        ];
        return Db::getInstance()->queryAll($sql, $params);
    }

    public function GetByProjId($proj_id)
    {
        $sql = "SELECT
                nbs_orders_status_action.id,
                nbs_department.department_name, 
                nbs_orders_status.status_name, 
                nbs_users.lasttname, 
                nbs_users.firstname,
                nbs_orders_status_action.created_at 
                FROM nbs_orders_status_action
                INNER JOIN nbs_department ON nbs_department.id = nbs_orders_status_action.department_id 
                INNER JOIN nbs_orders_status ON nbs_orders_status.id = nbs_orders_status_action.orders_status_id
                INNER JOIN nbs_users ON nbs_users.id = nbs_orders_status_action.user_id 
                WHERE nbs_orders_status_action.proj_id = :proj_id
                ORDER by nbs_orders_status_action.created_at ASC ";

        $params = [
            'proj_id' => $proj_id
        ];

        return Db::getInstance()->queryAll($sql, $params);


    }

    // Был ли статус

    public function getStatusCheck($proj_id, $orders_status_id)
    {
        $tableName = $this->getTableName();
        $sql = "SELECT COUNT(*) as total FROM {$tableName}
        WHERE proj_id=:proj_id
        AND orders_status_id=:orders_status_id
        ORDER by created_at  DESC 
        LIMIT 1";
        $params = [
            'proj_id' => $proj_id,
            'orders_status_id' => $orders_status_id
        ];
        return Db::getInstance()->queryAll($sql, $params);
    }

}
