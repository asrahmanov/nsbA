<?php

namespace app\models\entities;
use app\models\Model;


class PoStatus extends Model
{
    public $status_id;
    protected $status_name;
    protected $created_at;
    protected $deleted;

    public function __construct(
        $status_name = null,
        $created_at = null,
        $deleted = null
    )
    {
        $this->status_name = $status_name;
        $this->created_at = $created_at;
        $this->deleted = $deleted;

        $this->arrayParams['status_name'] = false;
        $this->arrayParams['created_at'] = false;
        $this->arrayParams['deleted'] = false;
    }

}
