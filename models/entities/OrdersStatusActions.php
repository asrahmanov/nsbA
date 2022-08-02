<?php



namespace app\models\entities;
use app\controllers\SessionController;
use app\models\Model;


class OrdersStatusActions extends Model
{
    public $id;
    protected $proj_id;
    protected $department_id;
    protected $orders_status_id;
    protected $user_id;
    protected $send;


    public function __construct(
        $proj_id = null,
        $department_id = null,
        $orders_status_id = null,
        $user_id = null,
        $send = 'NO'
    )
    {
        $this->proj_id = $proj_id;
        $this->department_id = $department_id;
        $this->orders_status_id = $orders_status_id;
        $this->user_id = $user_id;
        $this->send = $send;

        $this->arrayParams['proj_id'] = false;
        $this->arrayParams['department_id'] = false;
        $this->arrayParams['orders_status_id'] = false;
        $this->arrayParams['user_id'] = false;
        $this->arrayParams['send'] = false;

    }


}
