<?php

namespace app\controllers;

use app\engine\App;
use app\models\entities\BiospecimenType;

class BiospecimenTypeController extends Controller
{
    protected $layout = 'admin';
    protected $defaultAction = 'biospecimenTypes';
    protected $render = 'biospecimenType/biospecimenTypes';

    public function actionBiospecimenTypes()
    {
        echo $this->render($this->render, [
            'pach' => $_SERVER["DOCUMENT_ROOT"]
        ]);
    }

    public function actionGetAll()
    {
        $array = App::call()->biospecimenTypeRepository->getAll();
        echo json_encode(['result' => $array]);
    }

    public function actionSave()
    {
        if (isset(App::call()->request->getParams()['id'])) {
            $id = App::call()->request->getParams()['id'];
            $item = App::call()->biospecimenTypeRepository->getObject($id);
        } else {
            $item = new BiospecimenType();
        }

        if (isset(App::call()->request->getParams()['biospecimen_type'])) {
            $biospecimen_type = App::call()->request->getParams()['biospecimen_type'];
            $item->biospecimen_type = $biospecimen_type;
        }
        if (isset(App::call()->request->getParams()['biospecimen_type_russian'])) {
            $biospecimen_type_russian = App::call()->request->getParams()['biospecimen_type_russian'];
            $item->biospecimen_type_russian = $biospecimen_type_russian;
        }

        $result = App::call()->biospecimenTypeRepository->save($item);

        echo $result;

    }

    public function actionDelete()
    {
        $id = App::call()->request->getParams()['id'];
        $biospecimenType = App::call()->biospecimenTypeRepository->getObject($id);
        $result = App::call()->biospecimenTypeRepository->delete($biospecimenType);
        echo json_encode(['result' => $result]);
    }

}
