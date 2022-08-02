<?php

namespace app\models\entities;
use app\models\Model;


class PoData extends Model
{
    public $id;
    protected $po_id;
    protected $fr_script_id;
    protected $nbs_scripts_staff_id;
    protected $country;
    protected $shipping_address;
    protected $contact;
    protected $phone;
    protected $email;

    public function __construct(
        $po_id = null,
        $fr_script_id = null,
        $nbs_scripts_staff_id = null,
        $country = null,
        $shipping_address = null,
        $contact = null,
        $phone = null,
        $email = null
    )
    {
        $this->po_id = $po_id;
        $this->fr_script_id = $fr_script_id;
        $this->nbs_scripts_staff_id = $nbs_scripts_staff_id;
        $this->country = $country;
        $this->shipping_address = $shipping_address;
        $this->contact = $contact;
        $this->phone = $phone;
        $this->email = $email;

        $this->arrayParams['po_id'] = false;
        $this->arrayParams['fr_script_id'] = false;
        $this->arrayParams['nbs_scripts_staff_id'] = false;
        $this->arrayParams['country'] = false;
        $this->arrayParams['shipping_address'] = false;
        $this->arrayParams['contact'] = false;
        $this->arrayParams['phone'] = false;
        $this->arrayParams['email'] = false;
    }

}
