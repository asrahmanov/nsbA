<?php

namespace app\controllers;

use app\engine\App;
use app\models\entities\OfferOption;

class OfferOptionController extends Controller
{

    public function actionGetByOfferId()
    {
        $offer_item_id = App::call()->request->getParams()['offer_id'];
        $array = App::call()->offerOptionRepository->getWhere([
            'offer_id' => $offer_item_id
        ]);
        echo json_encode(['result' => $array]);
    }

    public function actionSave()
    {
        if (isset(App::call()->request->getParams()['id']) && App::call()->request->getParams()['id']) {
            $id = App::call()->request->getParams()['id'];
            $item = App::call()->offerOptionRepository->getObject($id);
        } else {
            $item = new OfferOption();
        }

        if (isset(App::call()->request->getParams()['offer_id'])) {
            $offer_id = App::call()->request->getParams()['offer_id'];
            $item->offer_id = $offer_id;
        }
        if (isset(App::call()->request->getParams()['newlines_1'])) {
            $newlines_1 = App::call()->request->getParams()['newlines_1'];
            $item->newlines_1 = $newlines_1;
        }
        if (isset(App::call()->request->getParams()['newlines_2'])) {
            $newlines_2 = App::call()->request->getParams()['newlines_2'];
            $item->newlines_2 = $newlines_2;
        }

        $result = App::call()->offerOptionRepository->save($item);

        echo $result;

    }

}
