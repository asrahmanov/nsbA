<?php

namespace app\models\entities;
use app\controllers\SessionController;
use app\models\Model;


class ManagerSiteCapabilityAnswer extends Model
{
    public $id;
    protected $manager_site_capability_id;
    protected $diseases_sitecapability_id;
    protected $tissues_sitecapability_id;
    protected $answer;
    protected $created_at;
    protected $deleted;


    public function __construct(
        $manager_site_capability_id = null,
        $diseases_sitecapability_id = null,
        $tissues_sitecapability_id = null,
        $created_at = null,
        $answer = null,
        $deleted = 0
    )
    {
        $this->manager_site_capability_id = $manager_site_capability_id;
        $this->diseases_sitecapability_id = $diseases_sitecapability_id;
        $this->tissues_sitecapability_id = $tissues_sitecapability_id;
        $this->answer = $answer;
        $this->created_at= $created_at;
        $this->deleted = $deleted;

        $this->arrayParams['manager_site_capability_id'] = false;
        $this->arrayParams['diseases_sitecapability_id'] = false;
        $this->arrayParams['tissues_sitecapability_id'] = false;
        $this->arrayParams['answer'] = false;
        $this->arrayParams['created_at'] = false;
        $this->arrayParams['deleted'] = false;

    }


}
