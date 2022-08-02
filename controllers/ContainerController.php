<?php

namespace app\controllers;
use app\engine\App;

class ContainerController extends Controller
{

    public function actionGetAll()
    {
        $array = App::call()->containerRepository->getAll();
        echo json_encode(['result' => $array]);
    }

}
