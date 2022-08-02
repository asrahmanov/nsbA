<?php



namespace app\models\entities;
use app\controllers\SessionController;
use app\models\Model;


class OrdersStatus extends Model
{
    public $id;
    protected $department_id;
    protected $status_name;
    protected $deleted;





    public function __construct(
        $department_id = null,
        $status_name = null,
        $deleted = null
    )
    {
        $this->department_id = $department_id;
        $this->status_name = $status_name;
        $this->deleted = $deleted;

        $this->arrayParams['department_id'] = false;
        $this->arrayParams['status_name'] = false;
        $this->arrayParams['deleted'] = false;

    }


}
