<?php

namespace app\models\repositories;

use app\models\entities\NewTickets;
use app\models\Repository;
use app\engine\Db;

class NewTicketsRepository extends Repository
{

    public function getTableName()
    {
        return 'nbs_new_tickets';
    }

    public function getIdName()
    {
        return 'id';
    }

    public function getEntityClass()
    {
        return NewTickets::class;
    }

    public function getByTargetManager($user_id)
    {
        $sql = "
            SELECT nnt.*, nntt.user_id as target_id
            FROM nbs_new_tickets nnt
                JOIN nbs_new_ticket_target nntt ON nnt.id = nntt.ticket_id
            WHERE nntt.user_id =:user_id 
        ";
        $params = [
            'user_id' => $user_id
        ];
        return Db::getInstance()->queryAll($sql, $params);
    }

    public function getByDate($date)
    {
        $sql = "
            SELECT *
            FROM nbs_new_tickets 
            WHERE DATE(closed_at) >=:dd 
        ";
        $params = [
            'dd' => $date
        ];
        return Db::getInstance()->queryAll($sql, $params);
    }


    public function getByDateTwo($dateOne, $dateTwo)
    {
        $sql = "
            SELECT *
            FROM nbs_new_tickets 
            WHERE DATE(closed_at) >=:dateOne AND DATE(closed_at) <=:dateTwo
        ";
        $params = [
            'dateOne' => $dateOne,
            'dateTwo' => $dateTwo
        ];
        return Db::getInstance()->queryAll($sql, $params);
    }

    public function getWhere($columsAndParams)
    {
        $tableName = $this->getTableName();
        $columsNames = '';
        $params = [];
        foreach ($columsAndParams as $key => $value) {
            $columsNames .= $key . "=:{$key} AND ";
            $params[$key] = $value;
        }
        $columsNames = substr($columsNames, 0, -5);
        $sql = "SELECT * FROM {$tableName} WHERE $columsNames
        LIMIT 100
        ";
        return Db::getInstance()->queryAll($sql, $params);
    }


}
