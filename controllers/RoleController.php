<?php

namespace app\controllers;

use app\engine\App;
use app\models\entities\Role;


class RoleController extends Controller
{
    protected $layout = 'vue';
    protected $defaultAction = 'Role';
    protected $render = 'admin/role';

    public function actionRole()
    {
        echo $this->render($this->render);
    }

    public function actionGetAll()
    {
        $array = App::call()->roleRepository->getAll();
        echo json_encode($array);
    }

    public function actionGetAllRoot()
    {
        $array = App::call()->roleRepository->getAllRoot();
        echo json_encode($array);
    }




}
