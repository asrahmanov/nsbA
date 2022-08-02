<?php



namespace app\models\entities;
use app\controllers\SessionController;
use app\models\Model;


class SiteTypes extends Model
{
    public $site_type_id;
    protected $site_type;

    public function __construct(
        $site_type = null
    )
    {
        $this->site_type = $site_type;

        $this->arrayParams['site_type'] = false;
    }


}
