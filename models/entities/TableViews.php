<?php

namespace app\models\entities;
use app\controllers\SessionController;
use app\models\Model;


class TableViews extends Model
{
    public $id;
    protected $user_id;
    protected $table_name;
    protected $column_name;
    protected $checkeds;




    public function __construct(
        $user_id = null,
        $table_name = null,
        $column_name = null,
        $checkeds = 1

    )
    {
        $this->user_id = $user_id;
        $this->table_name = $table_name;
        $this->column_name = $column_name;
        $this->checkeds = $checkeds;

        $this->arrayParams['user_id'] = false;
        $this->arrayParams['table_name'] = false;
        $this->arrayParams['column_name'] = false;
        $this->arrayParams['checkeds'] = false;

    }


}
