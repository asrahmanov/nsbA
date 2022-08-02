<?php

namespace app\models\entities;
use app\controllers\SessionController;
use app\models\Model;


class PoQuote extends Model
{
    public $id;
    protected $user_id;
    protected $proj_id;
    protected $site_id;
    protected $value_mount;
    protected $days;
    protected $price;
    protected $price_doc;
    protected $doc_name;
    protected $doc_id;
    protected $comment;
    protected $disease_id;
    protected $sample_id;
    protected $deleted;
    protected $created_at;
    protected $disabled;
    protected $quote_value;
    protected $quote_info;
    protected $quote_id;



    public function __construct(
        $user_id = null,
        $proj_id = null,
        $site_id = null,
        $value_mount = null,
        $days = null,
        $price = null,
        $price_doc = null,
        $doc_name = null,
        $doc_id = null,
        $comment = null,
        $disease_id = null,
        $sample_id = null,
        $deleted = 0,
        $disabled = 0,
        $quote_value = 0,
        $quote_info = null,
        $quote_id = 0
    )
    {
        $this->user_id = $user_id;
        $this->proj_id = $proj_id;
        $this->site_id = $site_id;
        $this->value_mount = $value_mount;
        $this->days = $days;
        $this->price = $price;
        $this->price_doc = $price_doc;
        $this->doc_name = $doc_name;
        $this->doc_id = $doc_id;
        $this->comment = $comment;
        $this->disease_id = $disease_id;
        $this->sample_id = $sample_id;
        $this->deleted = $deleted;
        $this->disabled = $disabled;
        $this->quote_value = $quote_value;
        $this->quote_info = $quote_info;
        $this->quote_id = $quote_id;

        $this->arrayParams['user_id'] = false;
        $this->arrayParams['proj_id'] = false;
        $this->arrayParams['site_id'] = false;
        $this->arrayParams['value_mount'] = false;
        $this->arrayParams['days'] = false;
        $this->arrayParams['price'] = false;
        $this->arrayParams['price_doc'] = false;
        $this->arrayParams['doc_name'] = false;
        $this->arrayParams['doc_id'] = false;
        $this->arrayParams['comment'] = false;
        $this->arrayParams['disease_id'] = false;
        $this->arrayParams['sample_id'] = false;
        $this->arrayParams['deleted'] = false;
        $this->arrayParams['created_at'] = false;
        $this->arrayParams['disabled'] = false;
        $this->arrayParams['quote_value'] = false;
        $this->arrayParams['quote_info'] = false;
        $this->arrayParams['quote_id'] = false;

    }

}
