<?php



namespace app\models\entities;
use app\controllers\SessionController;
use app\models\Model;


class ManagerSiteCapabilityQuestion extends Model
{
    public $id;
    protected $manager_site_capability_id;
    protected $question_id;
    protected $template_id;
    protected $site_id;
    protected $user_id;
    protected $question;
    protected $created_at;
    protected $deleted;

    public function __construct(
        $manager_site_capability_id = null,
        $question_id = null,
        $template_id = null,
        $site_id = null,
        $user_id = null,
        $question = null,
        $created_at = null,
        $deleted = null
    )
    {
        $this->manager_site_capability_id = $manager_site_capability_id;
        $this->question_id = $question_id;
        $this->template_id = $template_id;
        $this->site_id = $site_id;
        $this->user_id = $user_id;
        $this->question = $question;
        $this->created_at = $created_at;
        $this->deleted  = $deleted;


        $this->arrayParams['manager_site_capability_id'] = false;
        $this->arrayParams['question_id'] = false;
        $this->arrayParams['template_id'] = false;
        $this->arrayParams['site_id'] = false;
        $this->arrayParams['user_id'] = false;
        $this->arrayParams['question'] = false;
        $this->arrayParams['created_at'] = false;
        $this->arrayParams['deleted'] = false;

    }


}
