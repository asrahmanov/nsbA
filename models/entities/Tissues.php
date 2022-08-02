<?php



namespace app\models\entities;
use app\controllers\SessionController;
use app\models\Model;


class Tissues extends Model
{
    public $id;
    protected $name;
    protected $user_id;
    protected $deleted;

    public function __construct(
        $name = null,
        $user_id = null,
        $deleted = null
    )
    {
        $this->name = $name;
        $this->user_id = $user_id;
        $this->deleted = $deleted;

        $this->arrayParams['name'] = false;
        $this->arrayParams['user_id'] = false;
        $this->arrayParams['deleted'] = false;

    }


}
