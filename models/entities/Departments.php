<?php



namespace app\models\entities;
use app\controllers\SessionController;
use app\models\Model;


class Departments extends Model
{
    public $id;
    protected $department_name;
    protected $deleted;





    public function __construct(
        $department_name = null,
        $deleted = null
    )
    {
        $this->department_name = $department_name;
        $this->deleted = $deleted;

        $this->arrayParams['department_name'] = false;
        $this->arrayParams['deleted'] = false;

    }


}
