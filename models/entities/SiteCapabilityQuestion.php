<?php



namespace app\models\entities;
use app\controllers\SessionController;
use app\models\Model;


class SiteCapabilityQuestion extends Model
{
    public $id;
    protected $template_id;
    protected $question;
    protected $users_group;
    protected $created_at;
    protected $deleted;

    public function __construct(
        $template_id = null,
        $question = null,
        $users_group= null,
        $created_at = null,
        $deleted = null
    )
    {
        $this->template_id = $template_id;
        $this->question = $question;
        $this->users_group = $users_group;
        $this->created_at = $created_at;
        $this->deleted  = $deleted;


        $this->arrayParams['template_id'] = false;
        $this->arrayParams['question'] = false;
        $this->arrayParams['users_group'] = false;
        $this->arrayParams['created_at'] = false;
        $this->arrayParams['deleted'] = false;

    }


}
