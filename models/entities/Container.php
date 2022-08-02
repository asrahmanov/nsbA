<?php

namespace app\models\entities;
use app\controllers\SessionController;
use app\models\Model;


class Container extends Model
{
    public $id;
    protected $container_name;
    protected $price_usd;
    protected $price_euro;
    protected $deleted;


    public function __construct(
        $container_name = null,
        $price_usd = 0,
        $price_euro = 0,
        $deleted = null
    )
    {
        $this->container_name = $container_name;
        $this->price_usd = $price_usd;
        $this->price_euro = $price_euro;
        $this->deleted = $deleted;

        $this->arrayParams['container_name'] = false;
        $this->arrayParams['price_usd'] = false;
        $this->arrayParams['price_euro'] = false;
        $this->arrayParams['deleted'] = false;

    }


}
