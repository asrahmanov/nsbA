<?php

namespace app\models\repositories;

use app\engine\Db;
use app\models\entities\NewTicketChats;
use app\models\Repository;


class NewTicketChatsRepository extends Repository
{

    public function getTableName ()
    {
        return 'nbs_new_ticket_chats';
    }

    public function getIdName ()
    {
        return 'id';
    }

    public function getEntityClass ()
    {
        return  NewTicketChats::class;
    }


    public function getCountMassageGroupTicket($ticket_id) {
        $sql = "
            SELECT COUNT(id) as total FROM nbs_new_ticket_chats 
            WHERE ticket_id =:ticket_id
            AND message IS NOT NULL
            GROUP by ticket_id;
        ";
        $params = [
            'ticket_id' => $ticket_id
        ];
        return Db::getInstance()->queryAll($sql, $params);
    }

}
