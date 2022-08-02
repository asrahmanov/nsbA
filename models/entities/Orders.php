<?php

namespace app\models\entities;
use app\controllers\SessionController;
use app\models\Model;


class Orders extends Model
{
    public $proj_id;
    protected $fr_date;
    protected $internal_id;
    protected $external_id;
    protected $cust_id;
    protected $project_name;
    protected $clinical_inclusion;
    protected $clinical_exclusion;
    protected $donor_info;
    protected $quote_date;
    protected $currency;
    protected $project_details_old;
    protected $other_requirements;
    protected $project_details;
    protected $quote_details;
    protected $quotes_site_managers;
    protected $old_status;
    protected $po_upload_url;
    protected $fr_status;
    protected $fr_status_date;
    protected $linked_fr_id;
    protected $manager_quotes;
    protected $feas_russian;
    protected $quote_upload_url;
    protected $fr_script_id;
    protected $attention_to;
    protected $script_number;
    protected $comments;
    protected $quotes_from_managers;
    protected $unformal_quote;
    protected $request_type;
    protected $quote_price;
    protected $answering_id;
    protected $priority_id;
    protected $deadline;
    protected $deleted;
    protected $deadline_post;
    protected $deadline_quote;
    protected $history_quotes_search;
    protected $historical_data;

    protected $status_manager;
    protected $status_project;
    protected $status_lpo;
    protected $status_ved;
    protected $status_boss;
    protected $status_client;
    protected $margin;
    protected $quote_type;
    protected $communication_date;
    protected $communication_comment;

    protected $priority;
    protected $lto_comment;
    protected $po_comment;

    protected $history_po;
    protected $file_qoute_send;


    public function __construct(
        $fr_date = '0000-00-00',
        $internal_id = null,
        $external_id = null,
        $cust_id = null,
        $project_name = '-',

        $clinical_inclusion = null,
        $clinical_exclusion = null,
        $donor_info = null,
        $quote_date = null,
        $currency = 0,
        $project_details_old = null,
        $other_requirements = null,
        $project_details = null,
        $quote_details = null,
        $quotes_site_managers = null,
        $old_status = null,
        $po_upload_url = null,

        $fr_status = 16,
        $fr_status_date = null,
        $manager_quotes = null,
        $feas_russian = null,
        $quote_upload_url = null,
        $fr_script_id = 0,
        $attention_to = 0,
        $script_number = 0,
        $comments = null,
        $quotes_from_managers = null,
        $unformal_quote = null,
        $linked_fr_id = null,
        $request_type = null,
        $purchase_order = null,
        $quote_price = 0,
        $answering_id = 0,
        $priority_id = 0,
        $deadline = null,
        $deleted = 0,
        $deadline_post =  null,
        $deadline_quote =  null,
        $history_quotes_search =  null,

        $status_manager = 5,
        $status_project = 10,
        $status_lpo = 15,
        $status_ved = 20,
        $status_boss = 21,
        $status_client = 26,
        $margin = 0,
        $history_po = null,
        $historical_data = null,
        $quote_type = 'standart',
        $communication_date = null,
        $communication_comment = null,
        $priority = 5,
        $lto_comment = null,
        $po_comment = null,
        $file_qoute_send = null

    )
    {
        $this->fr_date = $fr_date;
        $this->internal_id = $internal_id;
        $this->external_id = $external_id;
        $this->cust_id = $cust_id;
        $this->project_name = $project_name;
        $this->clinical_inclusion = $clinical_inclusion;
        $this->clinical_exclusion = $clinical_exclusion;
        $this->donor_info = $donor_info;
        $this->quote_date = $quote_date;
        $this->currency = $currency;
        $this->project_details_old = $project_details_old;
        $this->other_requirements = $other_requirements;
        $this->project_details = $project_details;
        $this->quote_details = $quote_details;
        $this->quotes_site_managers = $quotes_site_managers;
        $this->old_status = $old_status;
        $this->po_upload_url = $po_upload_url;
        $this->fr_status = $fr_status;
        $this->fr_status_date = $fr_status_date;
        $this->linked_fr_id = $linked_fr_id;
        $this->manager_quotes = $manager_quotes;
        $this->feas_russian = $feas_russian;
        $this->quote_upload_url = $quote_upload_url;
        $this->fr_script_id = $fr_script_id;
        $this->attention_to = $attention_to;
        $this->script_number = $script_number;
        $this->comments = $comments;
        $this->quotes_from_managers = $quotes_from_managers;
        $this->unformal_quote = $unformal_quote;
        $this->request_type = $request_type;
        $this->purchase_order = $purchase_order;
        $this->quote_price = $quote_price;
        $this->answering_id = $answering_id;
        $this->priority_id = $priority_id;
        $this->deadline = $deadline;
        $this->deleted = $deleted;
        $this->deadline_post = $deadline_post;
        $this->deadline_post = $deadline_quote;
        $this->history_quotes_search = $history_quotes_search;
        $this->status_manager = $status_manager;
        $this->status_project = $status_project;
        $this->status_lpo = $status_lpo;
        $this->status_ved = $status_ved;
        $this->status_boss = $status_boss;
        $this->status_client = $status_client;
        $this->margin = $margin;
        $this->history_po = $history_po;
        $this->historical_data = $historical_data;
        $this->quote_type = $quote_type;
        $this->communication_date = $communication_date;
        $this->communication_comment = $communication_comment;
        $this->priority = $priority;
        $this->lto_comment = $lto_comment;
        $this->po_comment = $po_comment;
        $this->file_qoute_send = $file_qoute_send;

        $this->arrayParams['fr_date'] = false;
        $this->arrayParams['internal_id'] = false;
        $this->arrayParams['external_id'] = false;
        $this->arrayParams['cust_id'] = false;
        $this->arrayParams['project_name'] = false;
        $this->arrayParams['clinical_inclusion'] = false;
        $this->arrayParams['clinical_exclusion'] = false;
        $this->arrayParams['donor_info'] = false;
        $this->arrayParams['currency'] = false;
        $this->arrayParams['project_details_old'] = false;
        $this->arrayParams['other_requirements'] = false;
        $this->arrayParams['project_details'] = false;
        $this->arrayParams['quote_details'] = false;
        $this->arrayParams['quotes_site_managers'] = false;
        $this->arrayParams['old_status'] = false;
        $this->arrayParams['po_upload_url'] = false;
        $this->arrayParams['fr_status'] = false;
        $this->arrayParams['fr_status_date'] = false;
        $this->arrayParams['linked_fr_id'] = false;
        $this->arrayParams['manager_quotes'] = false;
        $this->arrayParams['feas_russian'] = false;
        $this->arrayParams['quote_upload_url'] = false;
        $this->arrayParams['fr_script_id'] = false;
        $this->arrayParams['attention_to'] = false;
        $this->arrayParams['script_number'] = false;
        $this->arrayParams['comments'] = false;
        $this->arrayParams['quotes_from_managers'] = false;
        $this->arrayParams['unformal_quote'] = false;
        $this->arrayParams['request_type'] = false;
        $this->arrayParams['purchase_order'] = false;
        $this->arrayParams['quote_price'] = false;
        $this->arrayParams['answering_id'] = false;
        $this->arrayParams['priority_id'] = false;
        $this->arrayParams['deadline'] = false;
        $this->arrayParams['deleted'] = false;
        $this->arrayParams['deadline_post'] = false;
        $this->arrayParams['deadline_quote'] = false;
        $this->arrayParams['history_quotes_search'] = false;

        $this->arrayParams['status_manager'] = false;
        $this->arrayParams['status_project'] = false;
        $this->arrayParams['status_lpo'] = false;
        $this->arrayParams['status_ved'] = false;
        $this->arrayParams['status_boss'] = false;
        $this->arrayParams['status_client'] = false;

        $this->arrayParams['margin'] = false;
        $this->arrayParams['history_po'] = false;
        $this->arrayParams['historical_data'] = false;
        $this->arrayParams['quote_type'] = false;
        $this->arrayParams['communication_date'] = false;
        $this->arrayParams['communication_comment'] = false;
        $this->arrayParams['priority'] = false;
        $this->arrayParams['lto_comment'] = false;
        $this->arrayParams['po_comment'] = false;
        $this->arrayParams['file_qoute_send'] = false;

    }


}
