<?php

namespace app\controllers;

use app\engine\App;
use app\models\entities\Pages;


class PagesController extends Controller
{
    protected $layout = 'admin';
    protected $defaultAction = 'pages';
    protected $render = 'admin/pages';

    public function actionPages()
    {
        echo $this->render($this->render);
    }

    public function actiongetAll()
    {
        $pages = App::call()->pagesRepository->getAll();
        echo json_encode($pages);
    }


    public function actionSave() {

        if (isset(App::call()->request->getParams()['id'])) {
            $id = App::call()->request->getParams()['id'];
            $page = App::call()->pagesRepository->getObject($id);

        } else {
            $page = New Pages();
        }

        if (isset(App::call()->request->getParams()['name'])) {
            $name= App::call()->request->getParams()['name'];
            $page->name = $name;
        }

        if (isset(App::call()->request->getParams()['alias'])) {
            $alias= App::call()->request->getParams()['alias'];
            $page->alias = $alias;
        }

        if (isset(App::call()->request->getParams()['template'])) {
            $template= App::call()->request->getParams()['template'];
            $page->template = $template;
        }


        $result = App::call()->pagesRepository->save($page);
        echo json_encode(['result' => $result]);

    }


    public function actionDell()
    {
        $id = App::call()->request->getParams()['id'];
        $page = App::call()->pagesRepository->getObject($id);
        $result = App::call()->pagesRepository->delete($page);
        echo json_encode(['result' => $result ]);
    }


}
