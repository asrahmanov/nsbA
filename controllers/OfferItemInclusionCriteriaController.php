<?php

namespace app\controllers;

use app\engine\App;
use app\models\entities\OfferItemInclusionCriteria;

class OfferItemInclusionCriteriaController extends Controller
{

    public function actionGetByOfferItemId()
    {
        $offer_item_id = App::call()->request->getParams()['offer_item_id'];
        $array = App::call()->offerItemInclusionCriteriaRepository->getWhere([
            'offer_item_id' => $offer_item_id
        ]);
        echo json_encode(['result' => $array]);
    }

    public function actionGetAll()
    {
        $array = App::call()->offerItemInclusionCriteriaRepository->getAll();
        echo json_encode(['result' => $array]);
    }

    public function actionSave()
    {
        if (isset(App::call()->request->getParams()['id'])) {
            $id = App::call()->request->getParams()['id'];
            $item = App::call()->offerItemInclusionCriteriaRepository->getObject($id);
        } else {
            $item = New OfferItemInclusionCriteria();
        }

        if (isset(App::call()->request->getParams()['offer_item_id'])) {
            $offer_item_id = App::call()->request->getParams()['offer_item_id'];
            $item->offer_item_id = $offer_item_id;
        }
        if (isset(App::call()->request->getParams()['inclusion_criteria'])) {
            $inclusion_criteria = App::call()->request->getParams()['inclusion_criteria'];
            $item->inclusion_criteria = $inclusion_criteria;
        }

        $result = App::call()->offerItemInclusionCriteriaRepository->save($item);

        echo $result;

    }

    public function actionDelete()
    {
        $id = App::call()->request->getParams()['id'];
        $offerItemInclusionCriteria = App::call()->offerItemInclusionCriteriaRepository->getObject($id);
        $result = App::call()->offerItemInclusionCriteriaRepository->delete($offerItemInclusionCriteria);
        echo json_encode(['result' => $result]);
    }

}
