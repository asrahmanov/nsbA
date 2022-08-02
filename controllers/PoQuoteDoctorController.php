<?php

namespace app\controllers;

use app\engine\App;
use app\models\entities\QuoteDoctor;


class PoQuoteDoctorController extends Controller
{

    public function actionSave()
    {
        if (isset(App::call()->request->getParams()['id'])) {
            $id = App::call()->request->getParams()['id'];
            $quote_doctor = App::call()->poquoteDoctorRepository->getObject($id);
        } else {
            $quote_doctor = New QuoteDoctor();
        }


        if (isset(App::call()->request->getParams()['quote_id'])) {
            $quote_doctor_id = App::call()->request->getParams()['quote_id'];
            $quote_doctor->quote_id = $quote_doctor_id;
        }
        if (isset(App::call()->request->getParams()['doc_id'])) {
            $doc_id = App::call()->request->getParams()['doc_id'];
            $quote_doctor->doc_id = $doc_id;
        }
        if (isset(App::call()->request->getParams()['doc_payment'])) {
            $doc_payment = App::call()->request->getParams()['doc_payment'];
            $quote_doctor->doc_payment = $doc_payment;
        }

        $result = App::call()->poquoteDoctorRepository->save($quote_doctor);

        echo json_encode(['result' => $result ]);

    }

    public function actionDell()
    {
            $id = App::call()->request->getParams()['id'];
            $quote_doctor = App::call()->poquoteDoctorRepository->getObject($id);
            $result = App::call()->poquoteDoctorRepository->delete($quote_doctor);
            echo json_encode(['result' => $result ]);
    }

}


