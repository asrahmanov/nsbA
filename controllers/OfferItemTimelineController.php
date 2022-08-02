<?php

namespace app\controllers;

use app\engine\App;
use app\models\entities\OfferItemTimeline;

class OfferItemTimelineController extends Controller
{

    public function actionGetByOfferItemId()
    {
        $offer_item_id = App::call()->request->getParams()['offer_item_id'];
        $array = App::call()->offerItemTimelineRepository->getWhere([
            'offer_item_id' => $offer_item_id
        ]);

        foreach ($array as $key => $el) {
            $sample_name = App::call()->biospecimenTypeRepository->getOne($el['sample_id']);
            if ($sample_name)
                $array[$key]['sample_name'] = $sample_name['biospecimen_type'];
            else
                $array[$key]['sample_name'] = '';
            $mod_name = App::call()->diseasesBiospecimenTypesRepository->getOne($el['sample_id']);
            if ($mod_name)
                $array[$key]['mod_name'] = $sample_name['mod_id'];
            else
                $array[$key]['mod_name'] = '';
        }
        echo json_encode(['result' => $array]);
    }

    public function actionSave()
    {
        if (isset(App::call()->request->getParams()['id'])) {
            $id = App::call()->request->getParams()['id'];
            $item = App::call()->offerItemTimelineRepository->getObject($id);
        } else {
            $item = New OfferItemTimeline();
        }

        if (isset(App::call()->request->getParams()['offer_item_id'])) {
            $offer_item_id = App::call()->request->getParams()['offer_item_id'];
            $item->offer_item_id = $offer_item_id;
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
        if (isset(App::call()->request->getParams()['sample_id'])) {
            $sample_id = App::call()->request->getParams()['sample_id'];
            $item->sample_id = $sample_id;
        }

        $result = App::call()->offerItemTimelineRepository->save($item);

        echo $result;
    }

    public function actionDelete()
    {
        $id = App::call()->request->getParams()['id'];
        $offerItemTimeline = App::call()->offerItemTimelineRepository->getObject($id);
        $result = App::call()->offerItemTimelineRepository->delete($offerItemTimeline);
        echo json_encode(['result' => $result ]);
    }

}


