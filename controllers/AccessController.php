<?php

namespace app\controllers;

use app\engine\App;
use app\models\entities\Access;


class AccessController extends Controller
{
    protected $layout = 'main';
    protected $defaultAction = 'access';
    protected $render = 'admin/access';

    public function actionAccess()
    {
        echo $this->render($this->render);
    }


    public function actionGetAll()
    {
        $array = App::call()->accessRepository->getAll();
        echo json_encode($array);
    }

    public function actionSave()
    {
        if (isset(App::call()->request->getParams()['id'])) {
            $id = App::call()->request->getParams()['id'];
            $access = App::call()->sitesRepository->getObject($id);
        } else {
            $access = New Access();
        }

        if (isset(App::call()->request->getParams()['role_id'])) {
            $role_id = App::call()->request->getParams()['role_id'];
            $access->role_id = $role_id;
        }

        if (isset(App::call()->request->getParams()['page_id'])) {
            $page_id = App::call()->request->getParams()['page_id'];
            $access->page_id = $page_id;
        }


        if (isset(App::call()->request->getParams()['deleted'])) {
            $deleted = App::call()->request->getParams()['deleted'];
            $access->deleted = $deleted;
        }

        $result = App::call()->accessRepository->save($access);

        echo json_encode(['result' => $result]);

    }


    public function actionDell()
    {
        $role_id = App::call()->request->getParams()['role_id'];
        $page_id = App::call()->request->getParams()['page_id'];
        $id = App::call()->accessRepository->getId($role_id,$page_id);
        $access= App::call()->accessRepository->getObject($id);
        $result = App::call()->accessRepository->delete($access);
        echo json_encode(['result' => $result ]);
    }

}
