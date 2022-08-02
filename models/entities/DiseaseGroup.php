<?php

namespace app\models\entities;
use app\controllers\SessionController;
use app\models\Model;


class DiseaseGroup extends Model
{
    public $id;
    protected $category_id;
    protected $group_name;

    public function __construct(
        $category_id = null,
        $group_name = null
    )
    {
        $this->category_id = $category_id;
        $this->group_name = $group_name;

        $this->arrayParams['category_id'] = false;
        $this->arrayParams['group_name'] = false;
    }

}
