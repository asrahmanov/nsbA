<?php

namespace app\models\entities;
use app\controllers\SessionController;
use app\models\Model;


class Poworksheets extends Model
{
    public $id;
    protected $proj_id;
    protected $user_id;
    protected $status_id;
    protected $comments;
    protected $deleted;


    public function __construct(
        $proj_id = null,
        $user_id = null,
        $status_id = null,
        $comments = null,
        $deleted = 0
    )
    {
        $this->proj_id = $proj_id;
        $this->user_id = $user_id;
        $this->status_id = $status_id;
        $this->deleted = $deleted;
        $this->comments = $comments;

        $this->arrayParams['proj_id'] = false;
        $this->arrayParams['user_id'] = false;
        $this->arrayParams['status_id'] = false;
        $this->arrayParams['deleted'] = false;
        $this->arrayParams['comments'] = false;

    }


}
