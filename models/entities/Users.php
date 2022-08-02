<?php

namespace app\models\entities;

use app\controllers\SessionController;
use app\models\Model;


class Users extends Model
{
    public $id;
    protected $firstname;
    protected $lasttname;
    protected $patronymic;
    protected $login;
    protected $password;
    protected $phone;
    protected $email;
    protected $role_id;
    protected $deleted;
    protected $color;
    protected $img_bg;
    protected $department_id;

    public function __construct(
        $firstname = null,
        $lasttname = null,
        $patronymic = null,
        $login = null,
        $password = null,
        $phone = null,
        $email = null,
        $role_id = 1,
        $img_bg = '../../app-assets/img/sidebar-bg/01.jpg',
        $color = 'success',
        $department_id = '1',
        $deleted = '0')
    {
        $this->firstname = $firstname;
        $this->lasttname = $lasttname;
        $this->patronymic = $patronymic;
        $this->login = $login;
        $this->password = $password;
        $this->phone = $phone;
        $this->email = $email;
        $this->role_id = $role_id;
        $this->deleted = $deleted;
        $this->img_bg = $img_bg;
        $this->color = $color;
        $this->department_id = $department_id;

        $this->arrayParams['firstname'] = false;
        $this->arrayParams['lasttname'] = false;
        $this->arrayParams['patronymic'] = false;
        $this->arrayParams['login'] = false;
        $this->arrayParams['password'] = false;
        $this->arrayParams['phone'] = false;
        $this->arrayParams['email'] = false;
        $this->arrayParams['role_id'] = false;
        $this->arrayParams['deleted'] = false;
        $this->arrayParams['color'] = false;
        $this->arrayParams['img_bg'] = false;
        $this->arrayParams['department_id'] = $department_id;

    }


}
