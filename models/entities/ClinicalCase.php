<?php

namespace app\models\entities;
use app\controllers\SessionController;
use app\models\Model;

class ClinicalCase extends Model
{
    public $id;
    protected $project_id;
	protected $site_id;
	protected $manager_id;
	protected $clinical_case_id;
	protected $site_index;
	protected $clinical_diagnosis;
	protected $clinical_date;
	protected $checked;
	protected $paid;
	protected $form_valid;
	protected $comment;
	protected $send_count;
	protected $last_send;
	protected $not_on_mailing_list;

    public function __construct(
        $project_id = null,
        $site_id = null,
        $manager_id = null,
        $clinical_case_id = null,
        $site_index = null,
        $clinical_diagnosis = null,
        $clinical_date =  null,
        $checked = '0',
        $paid = '0',
        $form_valid = '0',
        $send_count = '0',
        $last_send = null,
        $comment = null,
        $not_on_mailing_list = 0
    )
    {
        $this->project_id = $project_id;
        $this->site_id = $site_id;
        $this->manager_id = $manager_id;
        $this->clinical_case_id = $clinical_case_id;
        $this->site_index = $site_index;
        $this->clinical_diagnosis = $clinical_diagnosis;
        $this->clinical_date = $clinical_date;
        $this->checked = $checked;
        $this->paid = $paid;
        $this->form_valid = $form_valid;
        $this->comment = $comment;
        $this->send_count = $send_count;
        $this->last_send = $last_send;
        $this->not_on_mailing_list = $not_on_mailing_list;

        $this->arrayParams['project_id'] = false;
        $this->arrayParams['site_id'] = false;
        $this->arrayParams['manager_id'] = false;
        $this->arrayParams['clinical_case_id'] = false;
        $this->arrayParams['site_index'] = false;
        $this->arrayParams['clinical_diagnosis'] = false;
        $this->arrayParams['clinical_date'] = false;
        $this->arrayParams['checked'] = false;
        $this->arrayParams['paid'] = false;
        $this->arrayParams['form_valid'] = false;
        $this->arrayParams['comment'] = false;
        $this->arrayParams['send_count'] = false;
        $this->arrayParams['last_send'] = false;
        $this->arrayParams['not_on_mailing_list'] = false;
    }

}
