<?php

namespace app\controllers;

use app\engine\App;
use app\models\entities\PoDisease;

class PoDiseaseController extends Controller
{

    public function actionSave ()
    {
        if (isset(App::call()->request->getParams()['id'])) {
            $id = App::call()->request->getParams()['id'];
            $item = App::call()->poDiseaseRepository->getObject($id);
        } else {
            $item = New PoDisease();
        }
        if (isset(App::call()->request->getParams()['proj_id'])) {
            $proj_id = App::call()->request->getParams()['proj_id'];
            $item->proj_id = $proj_id;
        }
        if (isset(App::call()->request->getParams()['disease_id'])) {
            $disease_id = App::call()->request->getParams()['disease_id'];
            $item->disease_id = $disease_id;
        }
        $result = App::call()->poDiseaseRepository->save($item);
        echo $result;
    }

    public function actionDelete ()
    {
        $id = App::call()->request->getParams()['id'];
        $poDisease = App::call()->poDiseaseRepository->getObject($id);
        $result = App::call()->poDiseaseRepository->delete($poDisease);
        echo json_encode(['result' => $result]);
    }

}
