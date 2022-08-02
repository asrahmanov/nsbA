<?php

namespace app\models\entities;

use app\models\Model;


class NewTicketChats extends Model
{
    public $id;
    protected $author_id;
    protected $ticket_id;
    protected $message;
    protected $viewed;

    public function __construct (
        $author_id = null,
        $ticket_id = null,
        $message = null,
        $viewed = null
    )
    {
        $this->author_id = $author_id;
        $this->ticket_id = $ticket_id;
        $this->message = $message;
        $this->viewed = $viewed;

        $this->arrayParams['author_id'] = false;
        $this->arrayParams['ticket_id'] = false;
        $this->arrayParams['message'] = false;
        $this->arrayParams['viewed'] = false;
    }

}
