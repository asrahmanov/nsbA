<?php

namespace app\models\entities;

use app\controllers\SessionController;
use app\models\Model;


class WorksheetsLaboratory extends Model
{
    public $id;
    protected $proj_id;
    protected $user_id;
    protected $sample;

    protected $material_intake;
    protected $material_intake_text;
    protected $material_intake_num;

    protected $expendable_materials;
    protected $expendable_materials_text;
    protected $expendable_materials_num;

    protected $financial_expenses;
    protected $financial_expenses_text;
    protected $financial_expenses_num;

    protected $comments;
    protected $created_at;
    protected $deleted;

    public function __construct(
        $proj_id = null,
        $user_id = null,
        $sample = null,

        $material_intake = null,
        $material_intake_text = null,
        $material_intake_num = null,

        $expendable_materials = null,
        $expendable_materials_text = null,
        $expendable_materials_num = null,

        $financial_expenses = null,
        $financial_expenses_text = null,
        $financial_expenses_num = null,

        $comments = null,
        $created_at = null,
        $deleted = 0
    )
    {
        $this->proj_id = $proj_id;
        $this->user_id = $user_id;
        $this->sample = $sample;

        $this->material_intake = $material_intake;
        $this->material_intake_text = $material_intake_text;
        $this->material_intake_num = $material_intake_num;

        $this->expendable_materials = $expendable_materials;
        $this->expendable_materials_text = $expendable_materials_text;
        $this->expendable_materials_num = $expendable_materials_num;

        $this->financial_expenses = $financial_expenses;
        $this->financial_expenses_text = $financial_expenses_text;
        $this->financial_expenses_num = $financial_expenses_num;

        $this->comments = $comments;
        $this->created_at = $created_at;
        $this->deleted = $deleted;

        $this->arrayParams['proj_id'] = false;
        $this->arrayParams['user_id'] = false;
        $this->arrayParams['sample'] = false;

        $this->arrayParams['material_intake'] = false;
        $this->arrayParams['material_intake_text'] = false;
        $this->arrayParams['material_intake_num'] = false;

        $this->arrayParams['expendable_materials'] = false;
        $this->arrayParams['expendable_materials_text'] = false;
        $this->arrayParams['expendable_materials_num'] = false;

        $this->arrayParams['financial_expenses'] = false;
        $this->arrayParams['financial_expenses_text'] = false;
        $this->arrayParams['financial_expenses_num'] = false;

        $this->arrayParams['comments'] = false;
        $this->arrayParams['created_at'] = false;
        $this->arrayParams['deleted'] = false;

    }


}
