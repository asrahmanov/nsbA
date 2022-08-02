<?php

namespace app\models\entities;
use app\models\Model;

class LogChange extends Model
{
    public $id;
    protected $user_id;
    protected $proj_id;
    protected $info;
    protected $created_at;
    protected $input_type;
    protected $deleted;


    public function __construct(
        $user_id = null,
        $proj_id = null,
        $info = null,
        $input_type = null, // ENUM('unformal_quote','historical_po'),
        $deleted = null


    )
    {
        $this->user_id = $user_id;
        $this->proj_id = $proj_id;
        $this->info = $info;
        $this->input_type = $input_type;
        $this->deleted = $deleted;

        $this->arrayParams['user_id'] = false;
        $this->arrayParams['proj_id'] = false;
        $this->arrayParams['info'] = false;
        $this->arrayParams['input_type'] = false;
        $this->arrayParams['deleted'] = false;
    }
}
