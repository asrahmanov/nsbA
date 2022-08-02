<?php

namespace app\controllers;

use app\engine\App;
use app\models\entities\OfferApeItem;

class OfferApeItemController extends Controller
{
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
        $array = App::call()->offerApeItemRepository->getAll();
        echo json_encode(['result' => $array]);
    }

    public function actionGetByOfferId()
    {
        $offer_id = App::call()->request->getParams()['offer_id'];
        $array = App::call()->offerApeItemRepository->getWhere([
            'offer_id' => $offer_id
        ]);
        echo json_encode(['result' => $array]);
    }

    public function actionSave()
    {
        if (isset(App::call()->request->getParams()['id'])) {
            $id = App::call()->request->getParams()['id'];
            $item = App::call()->offerApeItemRepository->getObject($id);
        } else {
            $item = New OfferApeItem();
        }

        if (isset(App::call()->request->getParams()['offer_id'])) {
            $offer_id = App::call()->request->getParams()['offer_id'];
            $item->offer_id = $offer_id;
        }

        if (isset(App::call()->request->getParams()['species'])) {
            $species = App::call()->request->getParams()['species'];
            $item->species = $species;
        }

        if (isset(App::call()->request->getParams()['hbs_type'])) {
            $hbs_type = App::call()->request->getParams()['hbs_type'];
            $item->hbs_type = $hbs_type;
        }

        if (isset(App::call()->request->getParams()['quantity'])) {
            $quantity = App::call()->request->getParams()['quantity'];
            $item->quantity = $quantity;
        }


        $result = App::call()->offerApeItemRepository->save($item);

        echo $result;

    }

    public function actionDelete()
    {
        $id = App::call()->request->getParams()['id'];
        $offerItem = App::call()->offerApeItemRepository->getObject($id);
        $result = App::call()->offerApeItemRepository->delete($offerItem);
        echo json_encode(['result' => $result]);
    }

}


