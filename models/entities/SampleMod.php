<?php

namespace app\models\entities;
use app\controllers\SessionController;
use app\models\Model;


class SampleMod extends Model
{
    public $id;
    protected $modification;

    public function __construct(
        $modification = ' '
    )
    {
        $this->modification = $modification;

        $this->arrayParams['modification'] = false;
    }

}
