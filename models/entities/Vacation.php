<?php

namespace app\models\entities;
use app\controllers\SessionController;
use app\models\Model;

class Vacation extends Model
{
    public $id;
    protected $user_id;
    protected $date_start;
    protected $date_end;
    protected $comment;
    protected $created_at;
    protected $deleted;

    public function __construct(
        $date_start = null,
        $date_end = null,
        $user_id = null,
        $comment = null,
        $created_at = null,
        $deleted = 0
    )
    {
        $this->user_id = $user_id;
        $this->date_start = $date_start;
        $this->date_end = $date_end;
        $this->comment = $comment;
        $this->created_at = $created_at;
        $this->deleted = $deleted;

        $this->arrayParams['user_id'] = false;
        $this->arrayParams['date_start'] = false;
        $this->arrayParams['date_end'] = false;
        $this->arrayParams['comment'] = false;
        $this->arrayParams['created_at'] = false;
        $this->arrayParams['deleted'] = false;
    }


}
