<?php

namespace app\controllers;
use app\engine\App;

class CourierController extends Controller
{


    public function actionGetAll()
    {
        $array = App::call()->courierRepository->getAll();
        echo json_encode(['result' => $array]);
    }

}
