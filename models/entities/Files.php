<?php

namespace app\models\entities;
use app\controllers\SessionController;
use app\models\Model;


class Files extends Model
{
    public $id;
    protected $proj_id;
    protected $name;
    protected $alias;
    protected $deleted;
    protected $info;


    public function __construct(
        $proj_id = null,
        $name = null,
        $alias = null,
        $deleted = '0',
        $info = 'none'
    )
    {
        $this->proj_id = $proj_id;
        $this->name = $name;
        $this->alias = $alias;
        $this->deleted = $deleted;
        $this->info = $info;

        $this->arrayParams['proj_id'] = false;
        $this->arrayParams['name'] = false;
        $this->arrayParams['alias'] = false;
        $this->arrayParams['deleted'] = false;
        $this->arrayParams['info'] = false;

    }


}
