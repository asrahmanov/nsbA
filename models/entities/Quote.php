<?php

namespace app\models\entities;
use app\controllers\SessionController;
use app\models\Model;


class Quote extends Model
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
        $deleted = 0
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

    }


}
