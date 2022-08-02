<?php

namespace app\controllers;

use app\engine\App;
use app\models\entities\OfferRuItemExclusionCriteria;

class OfferRuItemExclusionCriteriaController extends Controller
{

    public function actionGetByOfferRuItemId()
    {
        $offer_ru_item_id = App::call()->request->getParams()['offer_ru_item_id'];
        $array = App::call()->offerRuItemExclusionCriteriaRepository->getWhere([
            'offer_ru_item_id' => $offer_ru_item_id
        ]);
        echo json_encode(['result' => $array]);
    }

    public function actionSave()
    {
        if (isset(App::call()->request->getParams()['id'])) {
            $id = App::call()->request->getParams()['id'];
            $item = App::call()->offerRuItemExclusionCriteriaRepository->getObject($id);
        } else {
            $item = new OfferRuItemExclusionCriteria();
        }

        if (isset(App::call()->request->getParams()['offer_ru_item_id'])) {
            $offer_ru_item_id = App::call()->request->getParams()['offer_ru_item_id'];
            $item->offer_ru_item_id = $offer_ru_item_id;
        }
        if (isset(App::call()->request->getParams()['exclusion_criteria'])) {
            $exclusion_criteria = App::call()->request->getParams()['exclusion_criteria'];
            $item->exclusion_criteria = $exclusion_criteria;
        }

        $result = App::call()->offerRuItemExclusionCriteriaRepository->save($item);

        echo $result;

    }

    public function actionDelete()
    {
        $id = App::call()->request->getParams()['id'];
        $offerRuItemExclusionCriteria = App::call()->offerRuItemExclusionCriteriaRepository->getObject($id);
        $result = App::call()->offerRuItemExclusionCriteriaRepository->delete($offerRuItemExclusionCriteria);
        echo json_encode(['result' => $result]);
    }

}
