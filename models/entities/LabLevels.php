<?php

namespace app\models\entities;

use app\models\Model;


class LabLevels extends Model
{
    public $id;
    protected $level_name;
    protected $deleted;

    public function __construct (
        $level_name = null,
        $deleted = null
    )
    {
        $this->level_name = $level_name;
        $this->deleted = $deleted;

        $this->arrayParams['level_name'] = false;
        $this->arrayParams['deleted'] = false;
    }

}
