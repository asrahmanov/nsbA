<?php

namespace app\models\entities;
use app\controllers\SessionController;
use app\models\Model;


class OrderDiseases extends Model
{
    public $id;
    protected $order_id;
    protected $disease_id;

    public function __construct(
        $order_id = null,
        $disease_id = null
    )
    {
        $this->order_id = $order_id;
        $this->disease_id = $disease_id;

        $this->arrayParams['order_id'] = false;
        $this->arrayParams['disease_id'] = false;
    }

}
