<?php

namespace app\controllers;

use app\engine\App;
use app\models\entities\RoleMenu;


class RoleMenuController extends Controller
{
    protected $layout = 'vue';
    protected $defaultAction = 'RoleMenu';
    protected $render = 'admin/roleMenu';

    public function actionRoleMenu()
    {
        echo $this->render($this->render);
    }

    public function actionGetAll()
    {
        $array = App::call()->rolemenuRepository->getAll();
        echo json_encode($array);
    }

    public function actionSave()
    {
        if (isset(App::call()->request->getParams()['id'])) {
            $id = App::call()->request->getParams()['id'];
            $menu = App::call()->rolemenuRepository->getObject($id);
        } else {
            $menu = New RoleMenu();
        }

        if (isset(App::call()->request->getParams()['name'])) {
            $name = App::call()->request->getParams()['name'];
            $menu->name = $name;
        }

        if (isset(App::call()->request->getParams()['alias'])) {
            $alias = App::call()->request->getParams()['alias'];
            $menu->alias = $alias;
        }

        if (isset(App::call()->request->getParams()['role_id'])) {
            $role_id = App::call()->request->getParams()['role_id'];
            $menu->role_id = $role_id;
        }

        if (isset(App::call()->request->getParams()['icon'])) {
            $icon = App::call()->request->getParams()['icon'];
            $menu->icon = $icon;
        }

        $result = App::call()->rolemenuRepository->save($menu);

        echo json_encode(['result' => $result]);

    }


    public function actionDell()
    {
        $role_id = App::call()->request->getParams()['role_id'];
        $alias = App::call()->request->getParams()['alias'];
        $id = App::call()->rolemenuRepository->getId($role_id,$alias);
        $menu= App::call()->rolemenuRepository->getObject($id);
        $result = App::call()->rolemenuRepository->delete($menu);
        echo json_encode(['result' => $result ]);
    }



}
