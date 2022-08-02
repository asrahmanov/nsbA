<?php

namespace app\controllers;

use app\engine\App;
use app\models\entities\Disease;
use Dompdf\Dompdf;

class DiseaseController extends Controller
{
    protected $layout = 'admin';
    protected $defaultAction = 'disease';
    protected $render = 'diseases/diseases';


    public function actionDisease()
    {
        echo $this->render($this->render, [
            'pach' => $_SERVER["DOCUMENT_ROOT"]
        ]);
    }

    public function actionGetAll()
    {
        $array = App::call()->diseaseRepository->getAll();
        echo json_encode(['result' => $array]);
    }

    public function actionSave()
    {
        if (isset(App::call()->request->getParams()['id'])) {
            $id = App::call()->request->getParams()['id'];
            $item = App::call()->diseaseRepository->getObject($id);
        } else {
            $item = new Disease();
        }

        if (isset(App::call()->request->getParams()['disease_name'])) {
            $disease_name = App::call()->request->getParams()['disease_name'];
            $item->disease_name = $disease_name;
        }
        if (isset(App::call()->request->getParams()['disease_name_russian'])) {
            $disease_name_russian = App::call()->request->getParams()['disease_name_russian'];
            $item->disease_name_russian = $disease_name_russian;
        }
        if (isset(App::call()->request->getParams()['disease_name_russian_old'])) {
            $disease_name_russian_old = App::call()->request->getParams()['disease_name_russian_old'];
            $item->disease_name_russian_old = $disease_name_russian_old;
        }
        if (isset(App::call()->request->getParams()['group_id'])) {
            $group_id = App::call()->request->getParams()['group_id'];
            $item->group_id = $group_id;
        }

        $result = App::call()->diseaseRepository->save($item);

        echo $result;

    }

}


