<?php



namespace app\models\entities;
use app\controllers\SessionController;
use app\models\Model;


class SiteLogWorkStatuses extends Model
{
    public $id;
    protected $work_status;
    protected $site_id;
    protected $user_id;
    protected $work_comment;
    protected $profile;
    protected $date_change;

    public function __construct(
        $work_status = null,
        $site_id = null,
        $user_id = null,
        $work_comment = null,
        $profile = null,
        $date_change = null
    )
    {
        $this->work_status = $work_status;
        $this->site_id = $site_id;
        $this->user_id = $user_id;
        $this->work_comment = $work_comment;
        $this->profile = $profile;
        $this->date_change = $date_change;

        $this->arrayParams['work_status'] = false;
        $this->arrayParams['site_id'] = false;
        $this->arrayParams['user_id'] = false;
        $this->arrayParams['work_comment'] = false;
        $this->arrayParams['profile'] = false;
        $this->arrayParams['date_change'] = false;
    }


}
