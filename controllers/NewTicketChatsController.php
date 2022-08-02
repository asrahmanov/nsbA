<?php

namespace app\controllers;

use app\engine\App;
use app\models\entities\NewTicketChats;


class NewTicketChatsController extends Controller
{

    public function actionSave()
    {
        if (isset(App::call()->request->getParams()['id'])) {
            $id = App::call()->request->getParams()['id'];
            $msg = App::call()->newTicketChatsRepository->getObject($id);
        } else {
            $msg = new NewTicketChats();
            $user_id = App::call()->session->getSession('user_id');
            $msg->author_id = $user_id;
        }

        if (isset(App::call()->request->getParams()['ticket_id'])) {
            $ticket_id = App::call()->request->getParams()['ticket_id'];
            $msg->ticket_id = $ticket_id;
        }
        if (isset(App::call()->request->getParams()['message'])) {
            $message = App::call()->request->getParams()['message'];
            $msg->message = $message;
        }
        $msg->viewed = 0;
        if (isset(App::call()->request->getParams()['viewed'])) {
            $viewed = App::call()->request->getParams()['viewed'];
            $msg->viewed = $viewed;
        }

        $result = App::call()->newTicketChatsRepository->save($msg);

        echo json_encode(['result' => $result]);

    }

}
