<?php

namespace app\controllers;

use app\engine\App;
use app\models\repositories\FrstatusRepository;
use app\models\entities\Frstatus;


class FrstatusController extends Controller
{
    protected $defaultAction = 'frstatus';


    public function actionFrstatus()
    {
        echo $this->render($this->defaultAction);
    }

    public function actionGetStatusAll()
    {
        $fr_status = App::call()->frstatusRepository->getAllDatatable();
        echo json_encode($fr_status);

    }

    public function actionGetAll()
    {
        $fr_status = App::call()->frstatusRepository->getAll();
        echo json_encode(['result' => $fr_status]);
    }


    public function actionInfo()
    {
        $statusId = App::call()->request->getParams()['statusId'];
        $status = App::call()->frstatusRepository->getOne($statusId);
        echo $this->render('statusInfo',['status'=> $status]);
    }


    public function actionSave()
    {

        if (isset(App::call()->request->getParams()['id'])) {
            $id = App::call()->request->getParams()['id'];
            $status = App::call()->frstatusRepository->getObject($id);

        } else {
            $status = New Frstatus();
        }

        $name = App::call()->request->getParams()['name'];
        $status->fr_status_values = $name;

        $result = App::call()->frstatusRepository->save($status);
        echo $result;
    }

}
