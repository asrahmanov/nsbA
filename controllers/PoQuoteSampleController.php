<?php

namespace app\controllers;

use app\engine\App;
use app\models\entities\QuoteSample;

class PoQuoteSampleController extends Controller
{

    public function actionSave()
    {
        if (isset(App::call()->request->getParams()['id'])) {
            $id = App::call()->request->getParams()['id'];
            $quote_sample = App::call()->poquoteSampleRepository->getObject($id);
        } else {

            $quote_sample = new QuoteSample();
        }

        if (isset(App::call()->request->getParams()['quote_id'])) {
            $quote_id = App::call()->request->getParams()['quote_id'];
            $quote_sample->quote_id = $quote_id;
        }
        if (isset(App::call()->request->getParams()['disease_id'])) {
            $disease_id = App::call()->request->getParams()['disease_id'];
            $quote_sample->disease_id = $disease_id;
        }
        if (isset(App::call()->request->getParams()['biospecimen_type_id'])) {
            $biospecimen_type_id = App::call()->request->getParams()['biospecimen_type_id'];
            $quote_sample->biospecimen_type_id = $biospecimen_type_id;
        }
        if (isset(App::call()->request->getParams()['mod_id'])) {
            $mod_id = App::call()->request->getParams()['mod_id'];
            $quote_sample->mod_id = $mod_id;
        }

        $result = App::call()->poquoteSampleRepository->save($quote_sample);

        echo json_encode(['result' => $result]);
    }

    public function actionDelete()
    {
        $id = App::call()->request->getParams()['id'];
        $quote_sample = App::call()->poquoteSampleRepository->getObject($id);
        $result = App::call()->poquoteSampleRepository->delete($quote_sample);
        echo json_encode(['result' => $result]);
    }

}


