<?php

namespace app\controllers;

use app\engine\App;
use app\models\entities\OrdersStatus;


class OrdersStatusController extends Controller
{


    public function actionGetAll()
    {
        $array = App::call()->ordersStatusRepository->getAll();
        echo json_encode(['result' => $array]);

    }






}
