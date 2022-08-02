<?php

namespace app\controllers;

use app\engine\App;
use app\models\entities\Priority;


class PriorityController extends Controller
{
    protected $defaultAction = 'priority';


    public function actionPriority()
    {
        echo $this->render($this->defaultAction);
    }

    public function actionGetAll()
    {
        $array = App::call()->sitesRepository->getAll();
       for($i = 0; $i < count($array); $i++ ) {
           $json['data'][$i]['id'] = $array[$i]['id'];
           $json['data'][$i]['name'] = $array[$i]['name'];
           $json['data'][$i]['color'] = $array[$i]['color'];
       }
        echo json_encode($json);

    }




}
