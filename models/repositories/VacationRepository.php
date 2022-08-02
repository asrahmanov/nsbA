<?php

namespace app\models\repositories;

use app\engine\App;
use app\models\entities\Vacation;
use app\models\Repository;
use app\engine\Db;


class VacationRepository extends Repository
{

    public function getTableName()
    {
        return 'nbs_vacation';
    }

    public function getEntityClass()
    {
        return Vacation::class;
    }

    public function getIdName()
    {
        return 'id';
    }

    public function checkVacation($date)
    {
        $user_id= App::call()->session->getSession('user_id');
        $date =  date("Y-m-d", strtotime($date));
        $sql = "SELECT COUNT(*) as total
        FROM nbs_vacation
        WHERE :dd BETWEEN nbs_vacation.date_start AND nbs_vacation.date_end
        and nbs_vacation.user_id = :user_id
        ";
        $params = [
            'dd' => $date,
            'user_id' => $user_id
        ];
        return Db::getInstance()->queryAll($sql, $params);
    }
    public function checkManagerVacation($date, $user_id)
    {

        $date =  date("Y-m-d", strtotime($date));
        $sql = "SELECT COUNT(*) as total
        FROM nbs_vacation
        WHERE :dd BETWEEN nbs_vacation.date_start AND nbs_vacation.date_end
        and nbs_vacation.user_id = :user_id
        ";


        $params = [
            'dd' => $date,
            'user_id' => $user_id
        ];


        return Db::getInstance()->queryAll($sql, $params);
    }


}
