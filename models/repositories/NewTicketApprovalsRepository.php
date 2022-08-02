<?php

namespace app\models\repositories;

use app\models\entities\NewTicketApprovals;
use app\models\Repository;


class NewTicketApprovalsRepository extends Repository
{

    public function getTableName ()
    {
        return 'nbs_new_ticket_approvals';
    }

    public function getIdName ()
    {
        return 'id';
    }

    public function getEntityClass ()
    {
        return  NewTicketApprovals::class;
    }

}
