<?php

namespace app\controllers;

use app\engine\App;
use app\models\entities\LabLevels;


class LabLevelsController extends Controller
{

/*
    public function actionGetAll() {
        $lab_levels = App::call()->labLevelsRepository->getAll();
        echo json_encode($lab_levels);
    }
*/

    public function actionSave() {
        if (isset(App::call()->request->getParams()['id'])) {
            $id = App::call()->request->getParams()['id'];
            $labLevel = App::call()->labLevelsRepository->getObject($id);
        } else {
            $labLevel = new LabLevels();
        }

        if (isset(App::call()->request->getParams()['level_name'])) {
            $level_name = App::call()->request->getParams()['level_name'];
            $labLevel->level_name = $level_name;
        }

        $result = App::call()->labLevelsRepository->save($labLevel);
        echo json_encode(['result' => $result]);

    }

}
