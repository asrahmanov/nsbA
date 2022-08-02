<?php

namespace app\controllers;

use app\engine\App;
use app\models\entities\OfferApeItemExclusionCriteria;

class OfferApeItemExclusionCriteriaController extends Controller
{

    public function actionGetByOfferItemId()
    {
        $offer_item_id = App::call()->request->getParams()['offer_ape_item_id'];
        $array = App::call()->offerApeItemExclusionCriteriaRepository->getWhere([
            'offer_ape_item_id' => $offer_item_id
        ]);
        echo json_encode(['result' => $array]);
    }

    public function actionSave()
    {
        if (isset(App::call()->request->getParams()['id'])) {
            $id = App::call()->request->getParams()['id'];
            $item = App::call()->offerApeItemExclusionCriteriaRepository->getObject($id);
        } else {
            $item = new OfferApeItemExclusionCriteria();
        }

        if (isset(App::call()->request->getParams()['offer_ape_item_id'])) {
            $offer_ape_item_id = App::call()->request->getParams()['offer_ape_item_id'];
            $item->offer_ape_item_id = $offer_ape_item_id;
        }
        if (isset(App::call()->request->getParams()['exclusion_criteria'])) {
            $exclusion_criteria = App::call()->request->getParams()['exclusion_criteria'];
            $item->exclusion_criteria = $exclusion_criteria;
        }

        $result = App::call()->offerApeItemExclusionCriteriaRepository->save($item);

        echo $result;

    }

    public function actionDelete()
    {
        $id = App::call()->request->getParams()['id'];
        $offerItemExclusionCriteria = App::call()->offerApeItemExclusionCriteriaRepository->getObject($id);
        $result = App::call()->offerApeItemExclusionCriteriaRepository->delete($offerItemExclusionCriteria);
        echo json_encode(['result' => $result]);
    }

}
