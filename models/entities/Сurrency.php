<?php

namespace app\models\entities;
use app\controllers\SessionController;
use app\models\Model;

class Сurrency extends Model
{
    public $currency_id;
    protected $currency;
    protected $currency_fullname;
    protected $symbol;

    public function __construct(
        $currency_id = null,
        $currency = null,
        $currency_fullname = null,
        $symbol = null
    )
    {
        $this->currency_id = $currency_id;
        $this->currency = $currency;
        $this->currency_fullname = $currency_fullname;
        $this->symbol = $symbol;

        $this->arrayParams['currency_id'] = false;
        $this->arrayParams['currency'] = false;
        $this->arrayParams['currency_fullname'] = false;
        $this->arrayParams['symbol'] = false;
    }


}
