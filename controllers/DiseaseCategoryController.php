<?php

namespace app\controllers;

use app\engine\App;
use app\models\entities\DiseaseCategory;

class DiseaseCategoryController extends Controller
{

    public function actionGetAll()
    {
        $array = App::call()->diseaseCategoryRepository->getAll();
        echo json_encode(['result' => $array]);
    }

    public function actionSave()
    {
        if (isset(App::call()->request->getParams()['id'])) {
            $id = App::call()->request->getParams()['id'];
            $item = App::call()->diseaseCategoryRepository->getObject($id);
        } else {
            $item = new DiseaseCategory();
        }

        if (isset(App::call()->request->getParams()['category_name'])) {
            $category_name = App::call()->request->getParams()['category_name'];
            $item->category_name = $category_name;
        }
        $result = App::call()->diseaseCategoryRepository->save($item);

        echo $result;

    }

}


