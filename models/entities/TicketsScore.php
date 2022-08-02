<?php

namespace app\models\entities;
use app\controllers\SessionController;
use app\models\Model;

class TicketsScore extends Model
{
    public $id;
    protected $ticket_id;
    protected $score;
    protected $action;
    protected $created_at;
    protected $deleted;

    public function __construct(
        $ticket_id = null,
        $score = null,
        $action = null,
        $created_at = null,
        $deleted = null
    )
    {
        $this->ticket_id = $ticket_id;
        $this->score = $score;
        $this->action = $action;
        $this->created_at = $created_at;
        $this->deleted = $deleted;

        $this->arrayParams['ticket_id'] = false;
        $this->arrayParams['score'] = false;
        $this->arrayParams['action'] = false;
        $this->arrayParams['created_at'] = false;
        $this->arrayParams['deleted'] = false;
    }


}
