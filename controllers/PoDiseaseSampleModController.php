<?php

namespace app\controllers;

use app\engine\App;
use app\models\entities\PoDiseaseSampleMod;

class PoDiseaseSampleModController extends Controller
{

    public function actionSave ()
    {
        if (isset(App::call()->request->getParams()['id'])) {
            $id = App::call()->request->getParams()['id'];
            $item = App::call()->poDiseaseSampleModRepository->getObject($id);
        } else {
            $item = New PoDiseaseSampleMod();
        }
        if (isset(App::call()->request->getParams()['proj_id'])) {
            $proj_id = App::call()->request->getParams()['proj_id'];
            $item->proj_id = $proj_id;
        }
        if (isset(App::call()->request->getParams()['disease_id'])) {
            $disease_id = App::call()->request->getParams()['disease_id'];
            $item->disease_id = $disease_id;
        }
        if (isset(App::call()->request->getParams()['sample_id'])) {
            $sample_id = App::call()->request->getParams()['sample_id'];
            $item->sample_id = $sample_id;
        }
        if (isset(App::call()->request->getParams()['mod_id'])) {
            $mod_id = App::call()->request->getParams()['mod_id'];
            if ($item->mod_id != $mod_id) {
                $proj_id = App::call()->request->getParams()['proj_id'];
                $disease_id = App::call()->request->getParams()['disease_id'];
                $sample_id = App::call()->request->getParams()['sample_id'];
                $modification = App::call()->request->getParams()['modification'];
                $modification_old = App::call()->sampleModRepository->getOne($item->mod_id);
//                $offer = App::call()->offerRepository->getWhere(['proj_id' => $proj_id])[0];
//                $offer_items = App::call()->offerItemRepository->getWhere([
//                    'offer_id' => $offer['id'],
//                    'disease_id' => $disease_id
//                ]);
//                foreach ($offer_items as $offer_item) {
//                    $offer_item_timelines = App::call()->offerItemTimelineRepository->getWhere([
//                        'offer_item_id' => $offer_item['id'],
//                        'sample_id' => $sample_id,
//                        'samples' => $modification_old['modification']
//                    ]);
//                    //var_dump($offer_item_timelines);
//                    foreach ($offer_item_timelines as $i => $offer_item_timeline) {
//                        $updated_timeline = App::call()->offerItemTimelineRepository->getObject($offer_item_timeline['id']);
//                        $updated_timeline->samples = $modification;
//                        App::call()->offerItemTimelineRepository->save($updated_timeline);
//                    }
//                }
            }
            $item->mod_id = $mod_id;
        }
        $result = App::call()->poDiseaseSampleModRepository->save($item);
        echo $result;
    }

    public function actionDelete ()
    {
        $id = App::call()->request->getParams()['id'];
        $poDiseaseSampleMod = App::call()->poDiseaseSampleModRepository->getObject($id);
        $result = App::call()->poDiseaseSampleModRepository->delete($poDiseaseSampleMod);
        echo json_encode(['result' => $result]);
    }

}
