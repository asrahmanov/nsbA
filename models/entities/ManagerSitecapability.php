<?php



namespace app\models\entities;
use app\controllers\SessionController;
use app\models\Model;


class ManagerSitecapability extends Model
{
    public $id;
    protected $template_id;
    protected $user_id;
    protected $site_id;
    protected $status_id;
    protected $update_at;
    protected $create_date;
    protected $deleted;

    public function __construct(
        $template_id = null,
        $user_id = null,
        $site_id = null,
        $status_id = null,
        $update_at = null,
        $create_date = null,
        $deleted = null
    )
    {
        $this->template_id = $template_id;
        $this->user_id = $user_id;
        $this->site_id = $site_id;
        $this->status_id = $status_id;
        $this->update_at = $update_at;
        $this->deleted = $deleted;
        $this->create_date = $create_date;

        $this->arrayParams['template_id'] = false;
        $this->arrayParams['user_id'] = false;
        $this->arrayParams['site_id'] = false;
        $this->arrayParams['status_id'] = false;
        $this->arrayParams['create_date'] = false;
        $this->arrayParams['deleted'] = false;
    }


}
