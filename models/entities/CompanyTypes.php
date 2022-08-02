<?php

namespace app\models\entities;

use app\models\Model;


class CompanyTypes extends Model
{
    public $id;
    protected $type_name;


    public function __construct (
        $type_name = null
    )
    {
        $this->type_name = $type_name;

        $this->arrayParams['type_name'] = false;
    }

}
