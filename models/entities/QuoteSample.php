<?php

namespace app\models\entities;
use app\models\Model;


class QuoteSample extends Model
{
    public $id;
    protected $quote_id;
    protected $disease_id;
    protected $biospecimen_type_id;
    protected $mod_id;
    protected $deleted;

    public function __construct(
        $quote_id = null,
        $disease_id = null,
        $biospecimen_type_id = null,
        $mod_id = null,
        $deleted = 0
    )

    {
        $this->quote_id = $quote_id;
        $this->disease_id = $disease_id;
        $this->biospecimen_type_id = $biospecimen_type_id;
        $this->mod_id = $mod_id;
        $this->deleted = $deleted;

        $this->arrayParams['quote_id'] = false;
        $this->arrayParams['disease_id'] = false;
        $this->arrayParams['biospecimen_type_id'] = false;
        $this->arrayParams['mod_id'] = false;
        $this->arrayParams['deleted'] = false;
    }

}
