<?php

namespace app\models\entities;
use app\controllers\SessionController;
use app\models\Model;


class Disease extends Model
{
    public $id;
    protected $group_id;
    protected $disease_name;
    protected $disease_name_russian;
    protected $disease_name_russian_old;

    public function __construct(
        $group_id = null,
        $disease_name = null,
        $disease_name_russian = null,
        $disease_name_russian_old = null
    )
    {
        $this->group_id = $group_id;
        $this->disease_name = $disease_name;
        $this->disease_name_russian = $disease_name_russian;
        $this->disease_name_russian_old = $disease_name_russian_old;

        $this->arrayParams['group_id'] = false;
        $this->arrayParams['disease_name'] = false;
        $this->arrayParams['disease_name_russian'] = false;
        $this->arrayParams['disease_name_russian_old'] = false;
    }

}
