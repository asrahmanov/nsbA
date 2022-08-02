<?php

namespace app\controllers;

use app\engine\App;
use app\models\entities\OfferItemListOfSamples;

class OfferItemListOfSamplesController extends Controller
{

    public function actionGetByOfferItemId()
    {
        $offer_item_id = App::call()->request->getParams()['offer_item_id'];
        $array = App::call()->offerItemListOfSamplesRepository->getWhere([
            'offer_item_id' => $offer_item_id
        ]);
        echo json_encode(['result' => $array]);
    }

    public function actionSave()
    {
        if (isset(App::call()->request->getParams()['id'])) {
            $id = App::call()->request->getParams()['id'];
            $item = App::call()->offerItemListOfSamplesRepository->getObject($id);
        } else {
            $item = new OfferItemListOfSamples();
        }

        if (isset(App::call()->request->getParams()['offer_item_id'])) {
            $offer_item_id = App::call()->request->getParams()['offer_item_id'];
            $item->offer_item_id = $offer_item_id;
        }
        if (isset(App::call()->request->getParams()['list_of_samples'])) {
            $list_of_samples = App::call()->request->getParams()['list_of_samples'];
            $item->list_of_samples = $list_of_samples;
        }

        $result = App::call()->offerItemListOfSamplesRepository->save($item);

        echo $result;

    }

    public function actionDelete()
    {
        $id = App::call()->request->getParams()['id'];
        $offerItemListOfSamples = App::call()->offerItemListOfSamplesRepository->getObject($id);
        $result = App::call()->offerItemListOfSamplesRepository->delete($offerItemListOfSamples);
        echo json_encode(['result' => $result]);
    }

}
