<?php



namespace app\models\entities;
use app\controllers\SessionController;
use app\models\Model;


class TissuesSitecapability extends Model
{
    public $id;
    protected $template_id;
    protected $tissues;
    protected $sorting;
    protected $users_group;
    protected $deleted;

    public function __construct(
        $template_id = null,
        $tissues= null,
        $sorting = null,
        $users_group = null,
        $deleted = null
    )
    {
        $this->template_id = $template_id;
        $this->tissues  = $tissues;
        $this->sorting = $sorting;
        $this->users_group = $users_group;
        $this->deleted = $deleted;

        $this->arrayParams['template_id'] = false;
        $this->arrayParams['tissues'] = false;
        $this->arrayParams['sorting'] = false;
        $this->arrayParams['users_group'] = false;
        $this->arrayParams['deleted'] = false;

    }


}
