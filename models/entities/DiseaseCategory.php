<?php

namespace app\models\entities;
use app\controllers\SessionController;
use app\models\Model;


class DiseaseCategory extends Model
{
    public $id;
    protected $category_name;

    public function __construct(
        $category_name = null
    )
    {
        $this->category_name = $category_name;

        $this->arrayParams['category_name'] = false;
    }

}
