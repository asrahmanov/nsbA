<?php

namespace app\models\entities;
use app\controllers\SessionController;
use app\models\Model;

class ClinicalCaseDraft extends Model
{
    public $id;
    public $clinical_case_id;
    public $user_id;

    public function __construct(
        $clinical_case_id = null,
        $user_id = null
    )
    {
        $this->clinical_case_id = $clinical_case_id;
        $this->user_id = $user_id;

        $this->arrayParams['clinical_case_id'] = false;
        $this->arrayParams['user_id'] = false;
    }

}
