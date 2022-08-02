<?php

namespace app\controllers;

use app\engine\App;
use app\models\entities\OfferRuItemInclusionCriteria;

class OfferRuItemInclusionCriteriaController extends Controller
{

    public function actionGetByOfferRuItemId()
    {
        $offer_ru_item_id = App::call()->request->getParams()['offer_ru_item_id'];
        $array = App::call()->offerRuItemInclusionCriteriaRepository->getWhere([
            'offer_ru_item_id' => $offer_ru_item_id
        ]);
        echo json_encode(['result' => $array]);
    }

    public function actionSave()
    {
        if (isset(App::call()->request->getParams()['id'])) {
            $id = App::call()->request->getParams()['id'];
            $item = App::call()->offerRuItemInclusionCriteriaRepository->getObject($id);
        } else {
            $item = New OfferRuItemInclusionCriteria();
        }

        if (isset(App::call()->request->getParams()['offer_ru_item_id'])) {
            $offer_ru_item_id = App::call()->request->getParams()['offer_ru_item_id'];
            $item->offer_ru_item_id = $offer_ru_item_id;
        }
        if (isset(App::call()->request->getParams()['inclusion_criteria'])) {
            $inclusion_criteria = App::call()->request->getParams()['inclusion_criteria'];
            $item->inclusion_criteria = $inclusion_criteria;
        }

        $result = App::call()->offerRuItemInclusionCriteriaRepository->save($item);

        echo $result;

    }

    public function actionDelete()
    {
        $id = App::call()->request->getParams()['id'];
        $offerRuItemInclusionCriteria = App::call()->offerRuItemInclusionCriteriaRepository->getObject($id);
        $result = App::call()->offerRuItemInclusionCriteriaRepository->delete($offerRuItemInclusionCriteria);
        echo json_encode(['result' => $result]);
    }

}
