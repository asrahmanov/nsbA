<?php

namespace app\controllers;

use app\engine\App;
use app\models\entities\NewTicketApprovals;
use app\models\entities\NewTicketChats;


class NewTicketApprovalsController extends Controller
{

    public function actionSave()
    {
        if (isset(App::call()->request->getParams()['id'])) {
            $id = App::call()->request->getParams()['id'];
            $approval = App::call()->newTicketApprovalsRepository->getObject($id);
        } else {
            $approval = new NewTicketApprovals();
        }

        if (isset(App::call()->request->getParams()['user_id'])) {
            $user_id = App::call()->request->getParams()['user_id'];
            $approval->user_id = $user_id;
        }
        if (isset(App::call()->request->getParams()['ticket_id'])) {
            $ticket_id = App::call()->request->getParams()['ticket_id'];
            $approval->ticket_id = $ticket_id;
        }
        if (isset(App::call()->request->getParams()['approvement'])) {
            $approvement = App::call()->request->getParams()['approvement'];
            $approval->approvement = $approvement;
        }

        $result = App::call()->newTicketApprovalsRepository->save($approval);

        if (isset(App::call()->request->getParams()['user_id']) &&
            isset(App::call()->request->getParams()['ticket_id']) &&
            isset(App::call()->request->getParams()['approvement'])) {
            $approvement = App::call()->request->getParams()['approvement'];
            $user_id = App::call()->request->getParams()['user_id'];
            $ticket_id = App::call()->request->getParams()['ticket_id'];
            $newChatMsg = new NewTicketChats();
            $newChatMsg->author_id = $user_id;
            $newChatMsg->ticket_id = $ticket_id;
            $newChatMsg->message = $approvement === '1' ? 'Согласовано' : 'Согласование отменено';
            App::call()->newTicketChatsRepository->save($newChatMsg);
            if ($approvement === '0') {
                App::call()->newTicketRatingRepository->deleteWhere([
                    'user_id' => $user_id,
                    'ticket_id' => $ticket_id
                ]);
            }
        }

        echo json_encode(['result' => $result]);

    }

}
