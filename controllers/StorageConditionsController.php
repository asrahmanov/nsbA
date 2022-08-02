<?php

namespace app\controllers;

use app\engine\App;
use app\models\entities\StorageConditions;

class StorageConditionsController extends Controller
{


    public function actionGetAll()
    {
        $array = App::call()->storageConditionsRepository->getAll();
        echo json_encode(['result' => $array]);
    }

    public function actionSave()
    {
        if (isset(App::call()->request->getParams()['id'])) {
            $id = App::call()->request->getParams()['id'];
            $item = App::call()->storageConditionsRepository->getObject($id);
        } else {
            $item = new StorageConditions();
        }

        if (isset(App::call()->request->getParams()['storage_conditions'])) {
            $storage_conditions = App::call()->request->getParams()['storage_conditions'];
            $item->storage_conditions = $storage_conditions;
        }

        $result = App::call()->storageConditionsRepository->save($item);

        echo $result;

    }

    public function actionDelete()
    {
        $id = App::call()->request->getParams()['id'];
        $storageConditions = App::call()->storageConditionsRepository->getObject($id);
        $result = App::call()->storageConditionsRepository->delete($storageConditions);
        echo json_encode(['result' => $result]);
    }

}
