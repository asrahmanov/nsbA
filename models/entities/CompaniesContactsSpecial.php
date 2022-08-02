<?php

namespace app\models\entities;

use app\models\Model;


class CompaniesContactsSpecial extends Model
{
    public $id;
    protected $special;


    public function __construct (
        $special = null
    )
    {
        $this->special = $special;

        $this->arrayParams['special'] = false;
    }

}
