<?php

namespace app\controllers;

use app\engine\App;
use app\models\entities\DiseaseGroup;

class DiseaseGroupController extends Controller
{

    public function actionGetAll()
    {
        $array = App::call()->diseaseGroupRepository->getAll();
        echo json_encode(['result' => $array]);
    }

    public function actionSave()
    {
        if (isset(App::call()->request->getParams()['id'])) {
            $id = App::call()->request->getParams()['id'];
            $item = App::call()->diseaseGroupRepository->getObject($id);
        } else {
            $item = new DiseaseGroup();
        }

        if (isset(App::call()->request->getParams()['category_id'])) {
            $category_id = App::call()->request->getParams()['category_id'];
            $item->category_id = $category_id;
        }
        if (isset(App::call()->request->getParams()['group_name'])) {
            $group_name = App::call()->request->getParams()['group_name'];
            $item->group_name = $group_name;
        }
        $result = App::call()->diseaseGroupRepository->save($item);

        echo $result;

    }

}


