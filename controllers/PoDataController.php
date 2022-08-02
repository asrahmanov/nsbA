<?php

namespace app\controllers;

use app\engine\App;
use app\models\entities\PoData;


class PoDataController extends Controller
{

    public function actionSave()
    {

        if (isset(App::call()->request->getParams()['id'])) {
            $id = App::call()->request->getParams()['id'];
            $po_data = App::call()->poDataRepository->getObject($id);
        } else {
            $po_data = new PoData();
        }

        if (isset(App::call()->request->getParams()['po_id'])) {
            $po_id = App::call()->request->getParams()['po_id'];
            $po_data->po_id = $po_id;
        }
        if (isset(App::call()->request->getParams()['fr_script_id'])) {
            $fr_script_id = App::call()->request->getParams()['fr_script_id'];
            $po_data->fr_script_id = $fr_script_id;
        }
        if (isset(App::call()->request->getParams()['nbs_scripts_staff_id'])) {
            $nbs_scripts_staff_id = App::call()->request->getParams()['nbs_scripts_staff_id'];
            $po_data->nbs_scripts_staff_id = $nbs_scripts_staff_id;
        }
        if (isset(App::call()->request->getParams()['country'])) {
            $country = App::call()->request->getParams()['country'];
            $po_data->country = $country;
        }
        if (isset(App::call()->request->getParams()['shipping_address'])) {
            $shipping_address = App::call()->request->getParams()['shipping_address'];
            $po_data->shipping_address = $shipping_address;
        }
        if (isset(App::call()->request->getParams()['contact'])) {
            $contact = App::call()->request->getParams()['contact'];
            $po_data->contact = $contact;
        }
        if (isset(App::call()->request->getParams()['phone'])) {
            $phone = App::call()->request->getParams()['phone'];
            $po_data->phone = $phone;
        }
        if (isset(App::call()->request->getParams()['email'])) {
            $email = App::call()->request->getParams()['email'];
            $po_data->email = $email;
        }

        $result = App::call()->poDataRepository->save($po_data);
        echo $result;

    }

}
