<?php

namespace app\controllers;

use app\engine\App;
use app\models\entities\OfferRuItemListOfSamples;

class OfferRuItemListOfSamplesController extends Controller
{

    public function actionGetByOfferRuItemId()
    {
        $offer_ru_item_id = App::call()->request->getParams()['offer_ru_item_id'];
        $array = App::call()->offerRuItemListOfSamplesRepository->getWhere([
            'offer_ru_item_id' => $offer_ru_item_id
        ]);
        echo json_encode(['result' => $array]);
    }

    public function actionSave()
    {
        if (isset(App::call()->request->getParams()['id'])) {
            $id = App::call()->request->getParams()['id'];
            $item = App::call()->offerRuItemListOfSamplesRepository->getObject($id);
        } else {
            $item = new OfferRuItemListOfSamples();
        }

        if (isset(App::call()->request->getParams()['offer_ru_item_id'])) {
            $offer_ru_item_id = App::call()->request->getParams()['offer_ru_item_id'];
            $item->offer_ru_item_id = $offer_ru_item_id;
        }
        if (isset(App::call()->request->getParams()['list_of_samples'])) {
            $list_of_samples = App::call()->request->getParams()['list_of_samples'];
            $item->list_of_samples = $list_of_samples;
        }

        $result = App::call()->offerRuItemListOfSamplesRepository->save($item);

        echo $result;

    }

    public function actionDelete()
    {
        $id = App::call()->request->getParams()['id'];
        $offerRuItemListOfSamples = App::call()->offerRuItemListOfSamplesRepository->getObject($id);
        $result = App::call()->offerRuItemListOfSamplesRepository->delete($offerRuItemListOfSamples);
        echo json_encode(['result' => $result]);
    }

}
