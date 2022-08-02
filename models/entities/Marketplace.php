<?php

namespace app\models\entities;

use app\controllers\SessionController;
use app\models\Model;


class Marketplace extends Model
{
    public $id;
    protected $short_name;
    protected $deleted;



    public function __construct(
        $short_name = null,
        $deleted = null

    )
    {
        $this->short_name = $short_name;
        $this->deleted = $deleted;


        $this->arrayParams['short_name'] = false;
        $this->arrayParams['deleted'] = false;


    }


}
