<?php

namespace app\models\repositories;

use app\engine\App;
use app\models\entities\TicketsScore;
use app\models\Repository;
use app\engine\Db;


class TicketsScoreRepository extends Repository
{

    public function getTableName()
    {
        return 'nbs_tickets_score';
    }

    public function getEntityClass()
    {
        return TicketsScore::class;
    }

    public function getIdName()
    {
        return 'id';
    }

    public function getScore($ticket_id){
        $tableName = $this->getTableName();
        $sql = "SELECT SUM(score) as score FROM nbs_tickets_score WHERE 
        ticket_id =:ticket_id
        ";
        $params = [
            'ticket_id' => $ticket_id
        ];
        return Db::getInstance()->queryAll($sql, $params);
    }

    public function finderror() {
        $sql = "SELECT  nts.id, ticket_id  FROM nbs_new_tickets nnt 
        INNER JOIN nbs_tickets_score nts ON nts.ticket_id = nnt.id 
        WHERE nts.score IS NULL
        AND date(nts.created_at) = date(nnt.created_at)
        AND nts.action = 'Задача принята';
        ";

        return Db::getInstance()->queryAll($sql);
    }




}
