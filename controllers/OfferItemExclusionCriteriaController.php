<?php

namespace app\controllers;

use app\engine\App;
use app\models\entities\OfferItemExclusionCriteria;

class OfferItemExclusionCriteriaController extends Controller
{

    public function actionGetByOfferItemId()
    {
        $offer_item_id = App::call()->request->getParams()['offer_item_id'];
        $array = App::call()->offerItemExclusionCriteriaRepository->getWhere([
            'offer_item_id' => $offer_item_id
        ]);
        echo json_encode(['result' => $array]);
    }

    public function actionSave()
    {
        if (isset(App::call()->request->getParams()['id'])) {
            $id = App::call()->request->getParams()['id'];
            $item = App::call()->offerItemExclusionCriteriaRepository->getObject($id);
        } else {
            $item = new OfferItemExclusionCriteria();
        }

        if (isset(App::call()->request->getParams()['offer_item_id'])) {
            $offer_item_id = App::call()->request->getParams()['offer_item_id'];
            $item->offer_item_id = $offer_item_id;
        }
        if (isset(App::call()->request->getParams()['exclusion_criteria'])) {
            $exclusion_criteria = App::call()->request->getParams()['exclusion_criteria'];
            $item->exclusion_criteria = $exclusion_criteria;
        }

        $result = App::call()->offerItemExclusionCriteriaRepository->save($item);

        echo $result;

    }

    public function actionDelete()
    {
        $id = App::call()->request->getParams()['id'];
        $offerItemExclusionCriteria = App::call()->offerItemExclusionCriteriaRepository->getObject($id);
        $result = App::call()->offerItemExclusionCriteriaRepository->delete($offerItemExclusionCriteria);
        echo json_encode(['result' => $result]);
    }

}
