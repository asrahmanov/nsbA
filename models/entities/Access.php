<?php



namespace app\models\entities;
use app\controllers\SessionController;
use app\models\Model;


class Access extends Model
{
    public $id;
    protected $role_id;
    protected $page_id;
    protected $deleted;

    public function __construct(
        $role_id = null,
        $page_id = null,
        $deleted = null
    )
    {
        $this->role_id = $role_id;
        $this->page_id = $page_id;
        $this->deleted = $deleted;

        $this->arrayParams['role_id'] = false;
        $this->arrayParams['page_id'] = false;
        $this->arrayParams['deleted'] = false;
    }


}
