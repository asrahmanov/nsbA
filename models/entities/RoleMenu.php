<?php



namespace app\models\entities;
use app\controllers\SessionController;
use app\models\Model;


class RoleMenu extends Model
{
    public $id;
    protected $role_id;
    protected $name;
    protected $alias;
    protected $icon;
    protected $deleted;

    public function __construct(
        $role_id = null,
        $name = null,
        $alias = null,
        $icon = null,
        $deleted = null
    )
    {
        $this->role_id = $role_id;
        $this->name = $name;
        $this->alias = $alias;
        $this->icon = $icon;
        $this->deleted = $deleted;

        $this->arrayParams['role_id'] = false;
        $this->arrayParams['name'] = false;
        $this->arrayParams['alias'] = false;
        $this->arrayParams['icon'] = false;
        $this->arrayParams['deleted'] = false;

    }


}
