<?php

namespace app\models\entities;

use app\models\Model;


class NewTicketRating extends Model
{
    public $id;
    protected $user_id;
    protected $ticket_id;
    protected $rating;
    protected $comment;

    public function __construct (
        $user_id = null,
        $ticket_id = null,
        $rating = null,
        $comment = null
    )
    {
        $this->user_id = $user_id;
        $this->ticket_id = $ticket_id;
        $this->rating = $rating;
        $this->comment = $comment;

        $this->arrayParams['user_id'] = false;
        $this->arrayParams['ticket_id'] = false;
        $this->arrayParams['rating'] = false;
        $this->arrayParams['comment'] = false;
    }

}
