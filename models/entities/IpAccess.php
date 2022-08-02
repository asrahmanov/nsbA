<?php

namespace app\models\entities;
use app\models\Model;

class IpAccess extends Model
{
    public $id;
    protected $ip;
    protected $created_at;
    protected $deleted;

    public function __construct(
        $ip = null,
        $created_at = null,
        $deleted = 0
    )
    {
        $this->ip = $ip;
        $this->created_at = $created_at;
        $this->deleted = $deleted;

        $this->arrayParams['ip'] = false;
        $this->arrayParams['created_at'] = false;
        $this->arrayParams['deleted'] = false;
    }


}
