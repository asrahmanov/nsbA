<?php

namespace app\models\entities;

use app\controllers\SessionController;
use app\models\Model;


class Company extends Model
{
    public $script_id;
    protected $script;
    protected $company_name;
    protected $contacts;
    protected $last_script_num;
    protected $currency;
    protected $currency_string;
    protected $script_type;
    protected $priority;
    protected $payment_terms;
    protected $legal_address;
    protected $origin;
    protected $status;
    protected $marketplace_id;
    protected $contract_off;
    protected $contract_comm;
    protected $is_contract;
    protected $deleted;


    public function __construct(
        $script = null,
        $company_name = null,
        $contacts = null,
        $last_script_num = null,
        $currency = null,
        $currency_string = null,
        $script_type = null,
        $priority = null,
        $payment_terms = null,
        $origin = null,
        $status = null,
        $marketplace_id = null,
        $contract_off = null,
        $contract_comm = null,
        $is_contract = '0',
        $legal_address = null,
        $deleted = '0'

    )
    {
        $this->script = $script;
        $this->company_name = $company_name;
        $this->contacts = $contacts;
        $this->last_script_num = $last_script_num;
        $this->currency = $currency;
        $this->currency_string = $currency_string;
        $this->script_type = $script_type;
        $this->priority = $priority;
        $this->currency = $currency;
        $this->payment_terms = $payment_terms;
        $this->legal_address = $legal_address;
        $this->origin = $origin;
        $this->status = $status;
        $this->contract_off = $contract_off;
        $this->contract_comm = $contract_comm;
        $this->is_contract = $is_contract;
        $this->marketplace_id = $marketplace_id;
        $this->deleted = $deleted;

        $this->arrayParams['script'] = false;
        $this->arrayParams['company_name'] = false;
        $this->arrayParams['contacts'] = false;
        $this->arrayParams['last_script_num'] = false;
        $this->arrayParams['currency'] = false;
        $this->arrayParams['currency_string'] = false;
        $this->arrayParams['script_type'] = false;
        $this->arrayParams['priority'] = false;
        $this->arrayParams['currency'] = false;
        $this->arrayParams['payment_terms'] = false;
        $this->arrayParams['legal_address'] = false;
        $this->arrayParams['origin'] = false;
        $this->arrayParams['status'] = false;
        $this->arrayParams['marketplace_id'] = false;
        $this->arrayParams['contract_off'] = false;
        $this->arrayParams['contract_comm'] = false;
        $this->arrayParams['is_contract'] = false;
        $this->arrayParams['marketplace_id'] = false;
        $this->arrayParams['deleted'] = false;


    }


}
