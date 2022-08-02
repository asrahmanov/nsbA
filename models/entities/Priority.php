<?php

namespace app\models\entities;
use app\controllers\SessionController;
use app\models\Model;


class Priority extends Model
{
    public $id;
    protected $name;
    protected $color;

    public function __construct(
        $name = null,
        $color = null
    )
    {
        $this->name = $name;
        $this->color = $color;
        $this->arrayParams['name'] = false;
        $this->arrayParams['color'] = false;

    }


}
