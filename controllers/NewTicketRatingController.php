<?php

namespace app\controllers;

use app\engine\App;
use app\models\entities\NewTicketRating;


class NewTicketRatingController extends Controller
{

    public function actionSave()
    {

        if (isset(App::call()->request->getParams()['id'])) {
            $id = App::call()->request->getParams()['id'];
            $ticket_rating = App::call()->newTicketRatingRepository->getObject($id);
        } else {
            $ticket_rating = new NewTicketRating();
        }
        if (isset(App::call()->request->getParams()['user_id'])) {
            $user_id = App::call()->request->getParams()['user_id'];
            $ticket_rating->user_id = $user_id;
        }
        if (isset(App::call()->request->getParams()['ticket_id'])) {
            $ticket_id = App::call()->request->getParams()['ticket_id'];
            $ticket_rating->ticket_id = $ticket_id;
        }
        if (isset(App::call()->request->getParams()['rating'])) {
            $rating = App::call()->request->getParams()['rating'];
            $ticket_rating->rating = $rating;
        }
        if (isset(App::call()->request->getParams()['comment'])) {
            $comment = App::call()->request->getParams()['comment'];
            $ticket_rating->comment = $comment;
        }

        $result = App::call()->newTicketRatingRepository->save($ticket_rating);

        echo json_encode(['result' => $result]);

    }

}
