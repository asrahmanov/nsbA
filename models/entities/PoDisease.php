<?php

namespace app\models\entities;
use app\models\Model;


class PoDisease extends Model
{
    public $id;
    protected $proj_id;
    protected $disease_id;

    public function __construct(
        $proj_id = null,
        $disease_id = null
    )
    {
        $this->proj_id = $proj_id;
        $this->disease_id = $disease_id;

        $this->arrayParams['proj_id'] = false;
        $this->arrayParams['disease_id'] = false;
    }

}
