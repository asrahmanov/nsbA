<?php

namespace app\models\entities;

use app\models\Model;


class Tickets extends Model
{
    public $id;
    protected $client_identifier;
    protected $mail_date;
    protected $task;
    protected $reply;
    protected $department_id;
    protected $author_id;
    protected $role_id;
    protected $worker_id;
    protected $created_at;
    protected $updated_at;
    protected $create_user_id;
    protected $update_user_id;



    protected $deleted;

    public function __construct (
        $client_identifier = null,
        $mail_date = null,
        $task = null,
        $reply = null,
        $department_id = null,
        $author_id = null,
        $role_id = null,
        $worker_id = null,
//        $created_at = null,
//        $updated_at = null,

        $create_user_id = null,
        $update_user_id = null,
        $status = null,
        $deleted = null
    )
    {
        $this->client_identifier = $client_identifier;
        $this->mail_date = $mail_date;
        $this->task = $task;
        $this->reply = $reply;
        $this->department_id = $department_id;
        $this->author_id = $author_id;
        $this->role_id = $role_id;
        $this->worker_id = $worker_id;
//        $this->created_at = $created_at;
//        $this->updated_at = $updated_at;
        $this->status = $status;
        $this->create_user_id = $create_user_id;
        $this->update_user_id = $update_user_id;
        $this->deleted = $deleted;

        $this->arrayParams['client_identifier'] = false;
        $this->arrayParams['mail_date'] = false;
        $this->arrayParams['task'] = false;
        $this->arrayParams['reply'] = false;
        $this->arrayParams['department_id'] = false;
        $this->arrayParams['author_id'] = false;
        $this->arrayParams['role_id'] = false;
        $this->arrayParams['worker_id'] = false;
//        $this->arrayParams['created_at'] = false;
//        $this->arrayParams['updated_at'] = false;
        $this->arrayParams['status'] = false;
        $this->arrayParams['create_user_id'] = false;
        $this->arrayParams['update_user_id'] = false;
        $this->arrayParams['deleted'] = false;
    }

}
