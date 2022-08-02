<?php

namespace app\controllers;

use app\engine\App;
use app\models\entities\OfferApeItemAssociatedInformation;

class OfferApeItemAssociatedInformationController extends Controller
{

    public function actionGetByOfferItemId()
    {
        $offer_item_id = App::call()->request->getParams()['offer_ape_item_id'];
        $array = App::call()->offerApeItemAssociatedInformationRepository->getWhere([
            'offer_ape_item_id' => $offer_item_id
        ]);
        echo json_encode(['result' => $array]);
    }

    public function actionSave()
    {
        if (isset(App::call()->request->getParams()['id'])) {
            $id = App::call()->request->getParams()['id'];
            $item = App::call()->offerApeItemAssociatedInformationRepository->getObject($id);
        } else {
            $item = New OfferApeItemAssociatedInformation();
        }

        if (isset(App::call()->request->getParams()['offer_ape_item_id'])) {
            $offer_item_id = App::call()->request->getParams()['offer_ape_item_id'];
            $item->offer_ape_item_id = $offer_item_id;
        }
        if (isset(App::call()->request->getParams()['associated_information'])) {
            $associated_information = App::call()->request->getParams()['associated_information'];
            $item->associated_information = $associated_information;
        }

        $result = App::call()->offerApeItemAssociatedInformationRepository->save($item);

        echo $result;

    }

    public function actionDelete()
    {
        $id = App::call()->request->getParams()['id'];
        $offerItemClinicalInformation = App::call()->offerApeItemAssociatedInformationRepository->getObject($id);
        $result = App::call()->offerApeItemAssociatedInformationRepository->delete($offerItemClinicalInformation);
        echo json_encode(['result' => $result]);
    }

}
