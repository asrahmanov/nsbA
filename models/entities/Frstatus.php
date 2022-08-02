<?php

namespace app\models\entities;
use app\controllers\SessionController;
use app\models\Model;


class frstatus extends Model
{
    public $fr_status_id;
    protected $fr_status_values;


    public function __construct(
        $fr_status_values = null
    )
    {
        $this->fr_status_values = $fr_status_values;

        $this->arrayParams['fr_status_values'] = false;
    }


}
