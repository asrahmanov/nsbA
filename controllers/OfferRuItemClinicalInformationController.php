<?php

namespace app\controllers;

use app\engine\App;
use app\models\entities\OfferRuItemClinicalInformation;

class OfferRuItemClinicalInformationController extends Controller
{

    public function actionGetByOfferRuItemId()
    {
        $offer_ru_item_id = App::call()->request->getParams()['offer_ru_item_id'];
        $array = App::call()->offerRuItemClinicalInformationRepository->getWhere([
            'offer_item_id' => $offer_ru_item_id
        ]);
        echo json_encode(['result' => $array]);
    }

    public function actionSave()
    {
        if (isset(App::call()->request->getParams()['id'])) {
            $id = App::call()->request->getParams()['id'];
            $item = App::call()->offerRuItemClinicalInformationRepository->getObject($id);
        } else {
            $item = New OfferRuItemClinicalInformation();
        }
        if (isset(App::call()->request->getParams()['offer_ru_item_id'])) {
            $offer_ru_item_id = App::call()->request->getParams()['offer_ru_item_id'];
            $item->offer_ru_item_id = $offer_ru_item_id;
        }
        if (isset(App::call()->request->getParams()['clinical_information'])) {
            $clinical_information = App::call()->request->getParams()['clinical_information'];
            $item->clinical_information = $clinical_information;
        }
        $result = App::call()->offerRuItemClinicalInformationRepository->save($item);
        echo $result;
    }

    public function actionDelete()
    {
        $id = App::call()->request->getParams()['id'];
        $offerRuItemClinicalInformation = App::call()->offerRuItemClinicalInformationRepository->getObject($id);
        $result = App::call()->offerRuItemClinicalInformationRepository->delete($offerRuItemClinicalInformation);
        echo json_encode(['result' => $result]);
    }

}
