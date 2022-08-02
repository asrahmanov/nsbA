<?php

namespace app\models\entities;
use app\controllers\SessionController;
use app\models\Model;


class QuoteDoctor extends Model
{
    public $id;
    protected $quote_id;
    protected $doc_id;
    protected $doc_payment;
    protected $deleted;


    public function __construct(
        $quote_id = null,
        $doc_id = null,
        $doc_payment = null,
        $deleted = 0
    )
    {
        $this->quote_id = $quote_id;
        $this->doc_id = $doc_id;
        $this->doc_payment = $doc_payment;
        $this->deleted = $deleted;

        $this->arrayParams['quote_id'] = false;
        $this->arrayParams['doc_id'] = false;
        $this->arrayParams['doc_payment'] = false;
        $this->arrayParams['deleted'] = false;

    }

}
