<?php

namespace app\controllers;

use app\engine\App;
use app\models\entities\SampleMod;

class SampleModController extends Controller
{

    public function actionGetAll()
    {
        $array = App::call()->sampleModRepository->getAll();
        echo json_encode(['result' => $array]);
    }

    public function actionGetFresh()
    {
        $array = App::call()->sampleModRepository->getFreshMods();
        echo json_encode(['result' => $array]);
    }



    public function actionDelete ()
    {
        $id = App::call()->request->getParams()['id'];
        $sampleMod = App::call()->sampleModRepository->getObject($id);
        $result = App::call()->sampleModRepository->delete($sampleMod);
        echo json_encode(['result' => $result]);
    }

    public function actionGetByMod()
    {
        $modification = App::call()->request->getParams()['modification'];
        $array = App::call()->sampleModRepository->getWhere([
            'modification' => $modification
        ]);
        echo json_encode($array);
    }

    public function actionSave ()
    {
        if (isset(App::call()->request->getParams()['id'])) {
            $id = App::call()->request->getParams()['id'];
            $item = App::call()->sampleModRepository->getObject($id);
        } else {
            $item = New SampleMod();
        }
        if (isset(App::call()->request->getParams()['modification'])) {
            $modification = App::call()->request->getParams()['modification'];
            $item->modification = $modification;
        }

        $result = App::call()->sampleModRepository->save($item);
        echo $result;
    }

}
