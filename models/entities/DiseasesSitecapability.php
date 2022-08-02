<?php



namespace app\models\entities;
use app\controllers\SessionController;
use app\models\Model;


class DiseasesSitecapability extends Model
{
    public $id;
    protected $template_id;
    protected $disease;
    protected $sorting;
    protected $users_group;
    protected $deleted;

    public function __construct(
        $template_id = null,
        $disease = null,
        $sorting = null,
        $users_group = null,
        $deleted = null
    )
    {
        $this->template_id = $template_id;
        $this->disease  = $disease;
        $this->sorting = $sorting;
        $this->users_group = $users_group;
        $this->deleted = $deleted;

        $this->arrayParams['template_id'] = false;
        $this->arrayParams['disease'] = false;
        $this->arrayParams['sorting'] = false;
        $this->arrayParams['users_group'] = false;
        $this->arrayParams['deleted'] = false;

    }


}
