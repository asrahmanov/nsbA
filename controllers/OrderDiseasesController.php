<?php

namespace app\controllers;

use app\engine\App;
use app\models\entities\OrderDiseases;

class OrderDiseasesController extends Controller
{

    public function actionGetAll()
    {
        $array = App::call()->orderDiseasesRepository->getAll();
        echo json_encode(['result' => $array]);
    }

    public function actionSave ()
    {
        if (isset(App::call()->request->getParams()['id'])) {
            $id = App::call()->request->getParams()['id'];
            $item = App::call()->orderDiseasesRepository->getObject($id);
        } else {
            $item = New OrderDiseases();
        }
        if (isset(App::call()->request->getParams()['order_id'])) {
            $order_id = App::call()->request->getParams()['order_id'];
            $item->order_id = $order_id;
        }
        if (isset(App::call()->request->getParams()['disease_id'])) {
            $disease_id = App::call()->request->getParams()['disease_id'];
            $item->disease_id = $disease_id;
        }
        $result = App::call()->orderDiseasesRepository->save($item);
        echo $result;
    }

    public function actionDelete ()
    {
        $id = App::call()->request->getParams()['id'];
        $orderDisease = App::call()->orderDiseasesRepository->getObject($id);
        $result = App::call()->orderDiseasesRepository->delete($orderDisease);
        echo json_encode(['result' => $result]);
    }

}
