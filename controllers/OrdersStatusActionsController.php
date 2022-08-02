<?php

namespace app\controllers;

use app\engine\App;
use app\models\entities\OrdersStatusActions;


class OrdersStatusActionsController extends Controller
{


    public function actionGetChengeStatus()
    {
        $array = App::call()->ordersStatusActionsRepository->getChengeStatus();
        echo json_encode(['result' => $array]);

    }

    public function actionGetLog()
    {
        $array = App::call()->ordersStatusActionsRepository->getLog();
        echo json_encode(['result' => $array]);
    }

    public function actionGetByProjId()
    {
        $proj_id = App::call()->request->getParams()['proj_id'];
        $array = App::call()->ordersStatusActionsRepository->GetByProjId($proj_id);
        echo json_encode(['result' => $array]);
    }

    public function actiongetAllGroupProj()
    {
        $array = App::call()->ordersStatusActionsRepository->getAll();

        $statusLog = [];
        for ($i = 0; $i < count($array); $i++) {
            $statusLog[$array[$i]['proj_id']][] = $array[$i]['orders_status_id'];
        }
        echo json_encode(['result' => $statusLog]);

    }

}
