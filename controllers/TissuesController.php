<?php

namespace app\controllers;

use app\engine\App;
use app\models\entities\Tissues;


class TissuesController extends Controller
{
    protected $layout = 'main';
    protected $defaultAction = 'Tissues';
    protected $render = 'tissues/tissues';

    public function actionTissues()
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
        $mkb = App::call()->tissuesRepository->getLike($text);
        echo json_encode($mkb);
    }

}
