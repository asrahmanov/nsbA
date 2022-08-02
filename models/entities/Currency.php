<?php



namespace app\models\entities;
use app\controllers\SessionController;
use app\models\Model;


class Currency extends Model
{
    public $currency_id;
    protected $currency;
    protected $currency_fullname;
    protected $symbol;

    public function __construct(
        $currency = null,
        $currency_fullname = null,
        $symbol = null
    )
    {
        $this->currency = $currency;
        $this->currency_fullname = $currency_fullname;
        $this->symbol = $symbol;

        $this->arrayParams['currency'] = false;
        $this->arrayParams['currency_fullname'] = false;
        $this->arrayParams['symbol'] = false;
    }


}
