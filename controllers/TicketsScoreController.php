<?php

namespace app\controllers;

use app\engine\App;
use app\models\entities\TicketsScore;
use Cassandra\Date;

class TicketsScoreController extends Controller
{

    public function actionGetAll()
    {
        $array = App::call()->ticketsScoreRepository->getAll();
        echo json_encode(['result' => $array]);
    }


    public function actionCreate()
    {

        $tiketScore = new TicketsScore();

        if (isset(App::call()->request->getParams()['ticket_id'])) {
            $ticket_id = App::call()->request->getParams()['ticket_id'];
            $tiketScore->ticket_id = $ticket_id;
        }

        if (isset(App::call()->request->getParams()['score'])) {
            $score = App::call()->request->getParams()['score'];
            $tiketScore->score = $score;
        }

        if (isset(App::call()->request->getParams()['action'])) {
            $action = App::call()->request->getParams()['action'];
            $tiketScore->action = $action;
        }


            $tiketScore->created_at = Date('Y-m-d H:i:s');



        echo $result = App::call()->ticketsScoreRepository->save($tiketScore);

    }

    public function actiongetInfobyTicketId()
    {
        $ticket_id = App::call()->request->getParams()['ticket_id'];
        $ticket = App::call()->ticketsScoreRepository->getWhere(['ticket_id' => $ticket_id]);
        echo json_encode($ticket);
    }

}
