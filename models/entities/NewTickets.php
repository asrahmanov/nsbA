<?php

namespace app\models\entities;

use app\models\Model;


class NewTickets extends Model
{
    public $id;
    protected $author_id;
    protected $target_id;
    protected $title;
    protected $message;
    protected $done;
    protected $deleted;
    protected $order_id;
    protected $priority;
    protected $reason;
    protected $deadline;
    protected $score;
    protected $closed_at;
    protected $closed_status;

    public function __construct (
        $author_id = null,
        $target_id = null,
        $title = null,
        $message = null,
        $done = '0',
        $deleted = null,
        $order_id = null,
        $priority = null,
        $reason = null,
        $deadline = null,
        $score = null,
        $closed_at = null,
        $closed_status = null
    )
    {
        $this->author_id = $author_id;
        $this->target_id = $target_id;
        $this->title = $title;
        $this->message = $message;
        $this->done = $done;
        $this->deleted = $deleted;
        $this->order_id = $order_id;
        $this->priority = $priority;
        $this->reason = $reason;
        $this->deadline = $deadline;
        $this->score = $score;
        $this->closed_at = $closed_at;
        $this->closed_status = $closed_status;

        $this->arrayParams['author_id'] = false;
        $this->arrayParams['target_id'] = false;
        $this->arrayParams['title'] = false;
        $this->arrayParams['message'] = false;
        $this->arrayParams['done'] = false;
        $this->arrayParams['deleted'] = false;
        $this->arrayParams['order_id'] = false;
        $this->arrayParams['priority'] = false;
        $this->arrayParams['reason'] = 'none';
        $this->arrayParams['deadline'] = false;
        $this->arrayParams['score'] = false;
        $this->arrayParams['closed_at'] = false;
        $this->arrayParams['closed_status'] = false;
    }

}
