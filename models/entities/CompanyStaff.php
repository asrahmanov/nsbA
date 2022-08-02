<?php



namespace app\models\entities;
use app\controllers\SessionController;
use app\models\Model;


class CompanyStaff extends Model
{
    public $id;
    protected $script_id;
    protected $name;
    protected $position;
    protected $email;
    protected $phone;
    protected $deleted;

    public function __construct(
        $name = null,
        $script_id = null,
        $position = null,
        $email = null,
        $phone = null,
        $deleted = null
    )
    {
        $this->name = $name;
        $this->script_id = $script_id;
        $this->position = $position;
        $this->email = $email;
        $this->phone = $phone;
        $this->deleted = $deleted;

        $this->arrayParams['script_id'] = false;
        $this->arrayParams['name'] = false;
        $this->arrayParams['position'] = false;
        $this->arrayParams['email'] = false;
        $this->arrayParams['phone'] = false;
        $this->arrayParams['deleted'] = false;

    }


}
