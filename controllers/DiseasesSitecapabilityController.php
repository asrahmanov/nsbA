<?php

namespace app\controllers;

use app\engine\App;

class DiseasesSitecapabilityController extends Controller
{

    public function actionGetAll()
    {
        $array = App::call()->diseasesSitecapabilityRepository->getAll();
        echo json_encode(['result' => $array]);
    }

}
