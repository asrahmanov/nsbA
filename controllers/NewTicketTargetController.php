<?php

namespace app\controllers;

use app\engine\App;
use app\models\entities\NewTicketTarget;


class NewTicketTargetController extends Controller
{

    public function actionSave()
    {

        if (isset(App::call()->request->getParams()['id'])) {
            $id = App::call()->request->getParams()['id'];
            $ticket_target = App::call()->newTicketTargetRepository->getObject($id);
        } else {
            $ticket_target = new NewTicketTarget();
        }

        if (isset(App::call()->request->getParams()['user_id'])) {
            $user_id = App::call()->request->getParams()['user_id'];
            $ticket_target->user_id = $user_id;
        }
        if (isset(App::call()->request->getParams()['ticket_id'])) {
            $ticket_id = App::call()->request->getParams()['ticket_id'];
            $ticket_target->ticket_id = $ticket_id;
        }

        $result = App::call()->newTicketTargetRepository->save($ticket_target);

        echo json_encode(['result' => $result]);

    }

}
