<?php

namespace app\models\entities;
use app\controllers\SessionController;
use app\models\Model;


class Shipping extends Model
{
    public $id;
    protected $shipping_name;
    protected $price_usd;
    protected $price_euro;
    protected $courier_id;
    protected $deleted;


    public function __construct(
        $shipping_name = null,
        $price_usd = 0,
        $price_euro = 0,
        $courier_id = 1,
        $deleted = null
    )
    {
        $this->shipping_name = $shipping_name;
        $this->price_usd = $price_usd;
        $this->price_euro = $price_euro;
        $this->courier_id = $courier_id;
        $this->deleted = $deleted;

        $this->arrayParams['shipping_name'] = false;
        $this->arrayParams['price_usd'] = false;
        $this->arrayParams['price_euro'] = false;
        $this->arrayParams['courier_id'] = false;
        $this->arrayParams['deleted'] = false;

    }


}
