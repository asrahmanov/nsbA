<?php

namespace app\controllers;

use app\engine\App;
use app\models\entities\OfferRuItem;

class OfferRuItemController extends Controller
{/*
    protected $layout = 'admin';
    protected $defaultAction = 'offer';
    protected $render = 'Commercial/offer';


    public function actionOffer()
    {
        echo $this->render($this->render, [
            'pach' => $_SERVER["DOCUMENT_ROOT"]
        ]);
    }

    public function actionGetAll()
    {
        $array = App::call()->offerItemRepository->getAll();
        echo json_encode(['result' => $array]);
    }
*/
    public function actionGetByOfferRuId()
    {
        $offer_ru_id = App::call()->request->getParams()['offer_ru_id'];
        $array = App::call()->offerRuItemRepository->getWhere([
            'offer_ru_id' => $offer_ru_id
        ]);
        echo json_encode(['result' => $array]);
    }

    public function actionSave()
    {
        if (isset(App::call()->request->getParams()['id'])) {
            $id = App::call()->request->getParams()['id'];
            $item = App::call()->offerRuItemRepository->getObject($id);
        } else {
            $item = New OfferRuItem();
        }
        if (isset(App::call()->request->getParams()['offer_ru_id'])) {
            $offer_ru_id = App::call()->request->getParams()['offer_ru_id'];
            $item->offer_ru_id = $offer_ru_id;
        }
        if (isset(App::call()->request->getParams()['disease'])) {
            $disease = App::call()->request->getParams()['disease'];
            $item->disease = $disease;
        }
        if (isset(App::call()->request->getParams()['hbs_type'])) {
            $hbs_type = App::call()->request->getParams()['hbs_type'];
            $item->hbs_type = $hbs_type;
        }
        if (isset(App::call()->request->getParams()['quantity'])) {
            $quantity = App::call()->request->getParams()['quantity'];
            $item->quantity = $quantity;
        }
        if (isset(App::call()->request->getParams()['specific_requirements'])) {
            $specific_requirements = App::call()->request->getParams()['specific_requirements'];
            $item->specific_requirements = $specific_requirements;
        }
        if (isset(App::call()->request->getParams()['ethnicity'])) {
            $ethnicity = App::call()->request->getParams()['ethnicity'];
            $item->ethnicity = $ethnicity;
        }
        if (isset(App::call()->request->getParams()['disease_id'])) {
            $disease_id = App::call()->request->getParams()['disease_id'];
            $item->disease_id = $disease_id;
        }
        if (isset(App::call()->request->getParams()['sample_id'])) {
            $sample_id = App::call()->request->getParams()['sample_id'];
            $item->sample_id = $sample_id;
        }
        if (isset(App::call()->request->getParams()['processing_details'])) {
            $processing_details = App::call()->request->getParams()['processing_details'];
            $item->processing_details = $processing_details;
        }
        if (isset(App::call()->request->getParams()['type_of_collection'])) {
            $type_of_collection = App::call()->request->getParams()['type_of_collection'];
            $item->type_of_collection = $type_of_collection;
        }
        if (isset(App::call()->request->getParams()['turnaround_time'])) {
            $turnaround_time = App::call()->request->getParams()['turnaround_time'];
            $item->turnaround_time = $turnaround_time;
        }

        $result = App::call()->offerRuItemRepository->save($item);

        echo $result;

    }

    public function actionDelete()
    {
        $id = App::call()->request->getParams()['id'];
        $offerRuItem = App::call()->offerRuItemRepository->getObject($id);
        $result = App::call()->offerRuItemRepository->delete($offerRuItem);
        echo json_encode(['result' => $result]);
    }

}


