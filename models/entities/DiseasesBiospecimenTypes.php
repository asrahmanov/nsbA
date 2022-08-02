<?php

namespace app\models\entities;
use app\controllers\SessionController;
use app\models\Model;


class DiseasesBiospecimenTypes extends Model
{
    public $id;
    protected $order_id;
    protected $disease_id;
    protected $biospecimen_type_id;
    protected $mod_id;
    protected $sample_count;

    public function __construct(
        $order_id = null,
        $disease_id = null,
        $biospecimen_type_id = null,
        $mod_id = null,
        $sample_count = null
    )
    {
        $this->order_id = $order_id;
        $this->disease_id = $disease_id;
        $this->biospecimen_type_id = $biospecimen_type_id;
        $this->mod_id = $mod_id;
        $this->sample_count = $sample_count;

        $this->arrayParams['order_id'] = false;
        $this->arrayParams['disease_id'] = false;
        $this->arrayParams['biospecimen_type_id'] = false;
        $this->arrayParams['mod_id'] = false;
        $this->arrayParams['sample_count'] = false;
    }

}
