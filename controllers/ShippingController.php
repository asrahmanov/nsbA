<?php

namespace app\controllers;
use app\engine\App;

class ShippingController extends Controller
{
    public function actionGetAllbyId()
    {

        $id = App::call()->request->getParams()['id'];
        $array = App::call()->shippingRepository->getAllbyId($id);
        echo json_encode(['result' => $array]);
    }

}
