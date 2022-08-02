<?php

namespace app\controllers;

use app\engine\App;
use app\models\entities\OfferApeItemInclusionCriteria;

class OfferApeItemInclusionCriteriaController extends Controller
{

    public function actionGetByOfferItemId()
    {
        $offer_item_id = App::call()->request->getParams()['offer_ape_item_id'];
        $array = App::call()->offerApeItemInclusionCriteriaRepository->getWhere([
            'offer_ape_item_id' => $offer_item_id
        ]);
        echo json_encode(['result' => $array]);
    }

    public function actionSave()
    {
        if (isset(App::call()->request->getParams()['id'])) {
            $id = App::call()->request->getParams()['id'];
            $item = App::call()->offerApeItemInclusionCriteriaRepository->getObject($id);
        } else {
            $item = New OfferApeItemInclusionCriteria();
        }

        if (isset(App::call()->request->getParams()['offer_ape_item_id'])) {
            $offer_ape_item_id = App::call()->request->getParams()['offer_ape_item_id'];
            $item->offer_ape_item_id = $offer_ape_item_id;
        }
        if (isset(App::call()->request->getParams()['inclusion_criteria'])) {
            $inclusion_criteria = App::call()->request->getParams()['inclusion_criteria'];
            $item->inclusion_criteria = $inclusion_criteria;
        }

        $result = App::call()->offerApeItemInclusionCriteriaRepository->save($item);

        echo $result;

    }

    public function actionDelete()
    {
        $id = App::call()->request->getParams()['id'];
        $offerItemInclusionCriteria = App::call()->offerApeItemInclusionCriteriaRepository->getObject($id);
        $result = App::call()->offerApeItemInclusionCriteriaRepository->delete($offerItemInclusionCriteria);
        echo json_encode(['result' => $result]);
    }

}
