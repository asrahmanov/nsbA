<?php

namespace app\models\repositories;

use app\models\entities\NewTicketTarget;
use app\models\Repository;


class NewTicketTargetRepository extends Repository
{

    public function getTableName ()
    {
        return 'nbs_new_ticket_target';
    }

    public function getIdName ()
    {
        return 'id';
    }

    public function getEntityClass ()
    {
        return  NewTicketTarget::class;
    }

}
