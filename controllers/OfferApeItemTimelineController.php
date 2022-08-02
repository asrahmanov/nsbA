<?php

namespace app\controllers;

use app\engine\App;
use app\models\entities\OfferApeItemTimeline;

class OfferApeItemTimelineController extends Controller
{

    public function actionGetByOfferItemId()
    {
        $offer_item_id = App::call()->request->getParams()['offer_ape_item_id'];
        $array = App::call()->offerApeItemTimelineRepository->getWhere([
            'offer_ape_item_id' => $offer_item_id
        ]);
        echo json_encode(['result' => $array]);
    }

    public function actionSave()
    {
        if (isset(App::call()->request->getParams()['id'])) {
            $id = App::call()->request->getParams()['id'];
            $item = App::call()->offerApeItemTimelineRepository->getObject($id);
        } else {
            $item = New OfferApeItemTimeline();
        }

        if (isset(App::call()->request->getParams()['offer_ape_item_id'])) {
            $offer_item_id = App::call()->request->getParams()['offer_ape_item_id'];
            $item->offer_ape_item_id = $offer_item_id;
        }
        if (isset(App::call()->request->getParams()['timeline'])) {
            $timeline = App::call()->request->getParams()['timeline'];
            $item->timeline = $timeline;
        }
        if (isset(App::call()->request->getParams()['samples'])) {
            $samples = App::call()->request->getParams()['samples'];
            $item->samples = $samples;
        }
        if (isset(App::call()->request->getParams()['type_of_collection'])) {
            $type_of_collection = App::call()->request->getParams()['type_of_collection'];
            $item->type_of_collection = $type_of_collection;
        }
        if (isset(App::call()->request->getParams()['processing_details'])) {
            $processing_details = App::call()->request->getParams()['processing_details'];
            $item->processing_details = $processing_details;
        }
        if (isset(App::call()->request->getParams()['price'])) {
            $price = App::call()->request->getParams()['price'];
            $item->price = $price;
        }

        $result = App::call()->offerApeItemTimelineRepository->save($item);

        echo $result;
    }

    public function actionDelete()
    {
        $id = App::call()->request->getParams()['id'];
        $offerItemTimeline = App::call()->offerApeItemTimelineRepository->getObject($id);
        $result = App::call()->offerApeItemTimelineRepository->delete($offerItemTimeline);
        echo json_encode(['result' => $result ]);
    }

}


