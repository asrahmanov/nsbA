<?php

namespace app\models\entities;
use app\models\Model;


class PoDiseaseSampleMod extends Model
{
    public $id;
    protected $proj_id;
    protected $disease_id;
    protected $sample_id;
    protected $mod_id;

    public function __construct(
        $proj_id = null,
        $disease_id = null,
        $sample_id = null,
        $mod_id = null
    )
    {
        $this->proj_id = $proj_id;
        $this->disease_id = $disease_id;
        $this->sample_id = $sample_id;
        $this->mod_id = $mod_id;

        $this->arrayParams['proj_id'] = false;
        $this->arrayParams['disease_id'] = false;
        $this->arrayParams['sample_id'] = false;
        $this->arrayParams['mod_id'] = false;
    }

}
