<?php

namespace app\controllers;

use app\engine\App;
use app\models\entities\OfferItemClinicalInformation;

class OfferItemClinicalInformationController extends Controller
{

    public function actionGetByOfferItemId()
    {
        $offer_item_id = App::call()->request->getParams()['offer_item_id'];
        $array = App::call()->offerItemClinicalInformationRepository->getWhere([
            'offer_item_id' => $offer_item_id
        ]);
        echo json_encode(['result' => $array]);
    }

    public function actionSave()
    {
        if (isset(App::call()->request->getParams()['id'])) {
            $id = App::call()->request->getParams()['id'];
            $item = App::call()->offerItemClinicalInformationRepository->getObject($id);
        } else {
            $item = New OfferItemClinicalInformation();
        }

        if (isset(App::call()->request->getParams()['offer_item_id'])) {
            $offer_item_id = App::call()->request->getParams()['offer_item_id'];
            $item->offer_item_id = $offer_item_id;
        }
        if (isset(App::call()->request->getParams()['clinical_information'])) {
            $clinical_information = App::call()->request->getParams()['clinical_information'];
            $item->clinical_information = $clinical_information;
        }

        $result = App::call()->offerItemClinicalInformationRepository->save($item);

        echo $result;

    }

    public function actionDelete()
    {
        $id = App::call()->request->getParams()['id'];
        $offerItemClinicalInformation = App::call()->offerItemClinicalInformationRepository->getObject($id);
        $result = App::call()->offerItemClinicalInformationRepository->delete($offerItemClinicalInformation);
        echo json_encode(['result' => $result]);
    }

}
