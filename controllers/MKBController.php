<?php

namespace app\controllers;

use app\engine\App;
use app\models\entities\Priority;


class MKBController extends Controller
{
    protected $layout = 'main';
    protected $defaultAction = 'mkb';
    protected $render = 'mkb/mkb';

    public function actionMkb()
    {
        echo $this->render($this->render);
    }

    public function actionGetAll()
    {
        $array = App::call()->mkbRepository->getAllDatatable();
        echo json_encode($array);
    }


    public function actiongetAlls()
    {
        $text = App::call()->request->getParams()['text'];
        $mkb = App::call()->mkbRepository->getLike($text);
        echo json_encode($mkb);
    }

}
