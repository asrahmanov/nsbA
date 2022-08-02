<?php

namespace app\models\entities;
use app\controllers\SessionController;
use app\models\Model;


class ManagerSites extends Model
{
    public $id;
    protected $user_id;
    protected $site_id;
    protected $created_at;
    protected $deleted;


    public function __construct(
        $user_id = null,
        $site_id = null,
        $deleted = 0
    )
    {
        $this->user_id = $user_id;
        $this->site_id = $site_id;
        $this->deleted = $deleted;

        $this->arrayParams['user_id'] = false;
        $this->arrayParams['site_id'] = false;
        $this->arrayParams['deleted'] = false;

    }


}
