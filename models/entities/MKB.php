<?php

namespace app\models\entities;
use app\controllers\SessionController;
use app\models\Model;


class MKB extends Model
{
    public $id;
    protected $code;
    protected $name;
    protected $class;


    public function __construct(
        $code = null,
        $name = null,
        $class = null
    )
    {
        $this->code = $code;
        $this->name = $name;
        $this->class = $class;

        $this->arrayParams['code'] = false;
        $this->arrayParams['name'] = false;
        $this->arrayParams['class'] = false;

    }


}
