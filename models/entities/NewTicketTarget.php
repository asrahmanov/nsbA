<?php

namespace app\models\entities;

use app\models\Model;


class NewTicketTarget extends Model
{
    public $id;
    protected $user_id;
    protected $ticket_id;

    public function __construct (
        $user_id = null,
        $ticket_id = null
    )
    {
        $this->user_id = $user_id;
        $this->ticket_id = $ticket_id;

        $this->arrayParams['user_id'] = false;
        $this->arrayParams['ticket_id'] = false;
    }

}
