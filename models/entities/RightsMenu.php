<?php

namespace app\models\entities;
use app\models\Model;


class RightsMenu extends Model
{
    public $id;
    public $role_id;
    public $name;
    public $alias;
    public $icon;
    public $parent_id;

    public function __construct($role_id = null, $name = null, $alias = null, $icon = null, $parent_id = null)
    {
        $this->role_id = $role_id;
        $this->name = $name;
        $this->alias = $alias;
        $this->icon = $icon;
        $this->parent_id = $parent_id;

        $this->arrayParams['role_id'] = false;
        $this->arrayParams['name'] = false;
        $this->arrayParams['alias'] = false;
        $this->arrayParams['icon'] = false;
        $this->arrayParams['parent_id'] = false;
    }

}
