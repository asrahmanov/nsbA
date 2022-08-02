<?php

namespace app\models\entities;

use app\models\Model;


class CompaniesContacts extends Model
{
    public $id;
    protected $company_id;
    protected $firstname;
    protected $lastname;
    protected $patronymic;
    protected $contact_type;
    protected $job;
    protected $contact_source;
    protected $work_phone;
    protected $home_phone;
    protected $other_phone;
    protected $work_email;
    protected $home_email;
    protected $address;
    protected $comment;
    protected $nosology;
    protected $active;
    protected $civil_contract;
    protected $newsletter;
    protected $special_id;
    protected $trigger_user_id;
    protected $quotable;
    protected $manager_point;
    protected $order_point;
    protected $nbs_point;


    public function __construct (
        $company_id = null,
        $firstname = null,
        $lastname = null,
        $patronymic = null,
        $contact_type = null,
        $job = null,
        $contact_source = null,
        $work_phone = null,
        $home_phone = null,
        $other_phone = null,
        $work_email = null,
        $home_email = null,
        $address = null,
        $comment = null,
        $nosology = null,
        $active = null,
        $civil_contract = null,
        $newsletter = null,
        $trigger_user_id = null,
        $special_id = null,
        $manager_point = 0,
        $order_point = 0,
        $nbs_point = 0,
        $quotable = null
    )
    {
        $this->company_id = $company_id;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->patronymic = $patronymic;
        $this->contact_type = $contact_type;
        $this->job = $job;
        $this->contact_source = $contact_source;
        $this->work_phone = $work_phone;
        $this->home_phone = $home_phone;
        $this->other_phone = $other_phone;
        $this->work_email = $work_email;
        $this->home_email = $home_email;
        $this->address = $address;
        $this->comment = $comment;
        $this->nosology = $nosology;
        $this->active = $active;
        $this->civil_contract = $civil_contract;
        $this->newsletter = $newsletter;
        $this->special_id = $special_id;
        $this->trigger_user_id = $trigger_user_id;
        $this->manager_point = $manager_point;
        $this->order_point = $order_point;
        $this->nbs_point = $nbs_point;
        $this->quotable = $quotable;

        $this->arrayParams['company_id'] = false;
        $this->arrayParams['firstname'] = false;
        $this->arrayParams['lastname'] = false;
        $this->arrayParams['patronymic'] = false;
        $this->arrayParams['contact_type'] = false;
        $this->arrayParams['contact_source'] = false;
        $this->arrayParams['work_phone'] = false;
        $this->arrayParams['home_phone'] = false;
        $this->arrayParams['other_phone'] = false;
        $this->arrayParams['work_email'] = false;
        $this->arrayParams['home_email'] = false;
        $this->arrayParams['address'] = false;
        $this->arrayParams['comment'] = false;
        $this->arrayParams['nosology'] = false;
        $this->arrayParams['active'] = false;
        $this->arrayParams['civil_contract'] = false;
        $this->arrayParams['newsletter'] = false;
        $this->arrayParams['special_id'] = false;
        $this->arrayParams['trigger_user_id'] = false;
        $this->arrayParams['manager_point'] = false;
        $this->arrayParams['order_point'] = false;
        $this->arrayParams['nbs_point'] = false;
        $this->arrayParams['quotable'] = false;
    }

}
