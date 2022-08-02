<?php

namespace app\models\entities;

use app\models\Model;


class NewTicketApprovals extends Model
{
    public $id;
    protected $user_id;
    protected $ticket_id;
    protected $approvement;

    public function __construct (
        $user_id = null,
        $ticket_id = null,
        $approvement = null
    )
    {
        $this->user_id = $user_id;
        $this->ticket_id = $ticket_id;
        $this->approvement = $approvement;

        $this->arrayParams['user_id'] = false;
        $this->arrayParams['ticket_id'] = false;
        $this->arrayParams['approvement'] = false;
    }

}
