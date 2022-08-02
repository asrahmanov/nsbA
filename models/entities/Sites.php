<?php

namespace app\models\entities;
use app\controllers\SessionController;
use app\models\Model;


class Sites extends Model
{
    public $site_id;
    protected $site_name;
    protected $site_code;
    protected $site_type;
    protected $city;
    protected $contract;
    protected $term_validity;
    protected $irb_approval;
    protected $site_status;
    protected $processing;
    protected $approved;
    protected $level;
    protected $misc;
    protected $timestamp1;
    protected $site_manager_primary;
    protected $addr;
    protected $text_city;
    protected $phone;
    protected $company_name;
    protected $company_type;
    protected $employee_quant;
    protected $site_addr;
    protected $email;
    protected $trigger_user_id;
    protected $level_id;
    protected $quotable;

    // Переменные для контроля менеджеров
    protected $work_status_manager;
    protected $work_status_auditor;
    protected $work_comment_manager;
    protected $work_comment_auditor;
    protected $auditor_confirm;
    protected $manager_confirm;


    public function __construct(
        $site_name = null,
        $site_code = null,
        $site_type = null,
        $city = null,
        $contract = null,
        $term_validity = null,
        $irb_approval = null,
        $site_status = null,
        $processing = null,
        $approved = null,
        $level = null,
        $timestamp1 = null,
        $site_manager_primary = null,
        $misc = null,
        $addr = null,
        $text_city = null,
        $phone = null,
        $company_name = null,
        $company_type = null,
        $employee_quant = null,
        $site_addr = null,
        $work_comment_manager = null,
        $work_comment_auditor = null,
        $work_status_manager = 0,
        $work_status_auditor = 0,
        $trigger_user_id = 0,
        $email = null,
        $auditor_confirm = null,
        $manager_confirm = null,
        $level_id = null,
        $quotable = null
    )
    {
        $this->site_name = $site_name;
        $this->site_code = $site_code;
        $this->site_type = $site_type;
        $this->city = $city;
        $this->contract = $contract;
        $this->term_validity = $term_validity;
        $this->irb_approval = $irb_approval;
        $this->site_status = $site_status;
        $this->processing = $processing;
        $this->approved = $approved;
        $this->level = $level;
        $this->misc = $misc;
        $this->timestamp1 = $timestamp1;
        $this->site_manager_primary = $site_manager_primary;
        $this->addr = $addr;
        $this->text_city = $text_city;
        $this->phone = $phone;
        $this->company_name = $company_name;
        $this->company_type = $company_type;
        $this->employee_quant = $employee_quant;
        $this->site_addr = $site_addr;
        $this->email = $email;
        $this->trigger_user_id = $trigger_user_id;
        $this->work_comment_manager = $work_comment_manager;
        $this->work_comment_auditor = $work_comment_auditor;
        $this->work_status_manager = $work_status_manager;
        $this->work_status_auditor = $work_status_auditor;
        $this->auditor_confirm = $auditor_confirm;
        $this->manager_confirm = $manager_confirm;
        $this->level_id = $level_id;
        $this->quotable = $quotable;

        $this->arrayParams['site_name'] = false;
        $this->arrayParams['site_code'] = false;
        $this->arrayParams['site_type'] = false;
        $this->arrayParams['city'] = false;
        $this->arrayParams['contract'] = false;
        $this->arrayParams['term_validity'] = false;
        $this->arrayParams['irb_approval'] = false;
        $this->arrayParams['site_status'] = false;
        $this->arrayParams['processing'] = false;
        $this->arrayParams['approved'] = false;
        $this->arrayParams['level'] = false;
        $this->arrayParams['misc'] = false;
        $this->arrayParams['site_manager_primary'] = false;
        $this->arrayParams['timestamp1'] = false;
        $this->arrayParams['addr'] = false;
        $this->arrayParams['text_city'] = false;
        $this->arrayParams['phone'] = false;
        $this->arrayParams['company_name'] = false;
        $this->arrayParams['company_type'] = false;
        $this->arrayParams['employee_quant'] = false;
        $this->arrayParams['site_addr'] = false;
        $this->arrayParams['email'] = false;
        $this->arrayParams['trigger_user_id'] = false;
        $this->arrayParams['level_id'] = false;
        $this->arrayParams['quotable'] = false;

        $this->arrayParams['work_status_auditor'] = false;
        $this->arrayParams['work_status_manager'] = false;
        $this->arrayParams['work_comment_auditor'] = false;
        $this->arrayParams['work_comment_manager'] = false;
        $this->arrayParams['auditor_confirm'] = false;
        $this->arrayParams['manager_confirm'] = false;
    }

}
