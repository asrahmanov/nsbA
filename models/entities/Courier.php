<?php

namespace app\models\entities;
use app\controllers\SessionController;
use app\models\Model;


class Courier extends Model
{
    public $id;
    protected $courier_name;
    protected $deleted;


    public function __construct(
        $courier_name = null,
        $deleted = null
    )
    {
        $this->courier_name = $courier_name;
        $this->deleted = $deleted;

        $this->arrayParams['courier_name'] = false;
        $this->arrayParams['deleted'] = false;

    }


}
