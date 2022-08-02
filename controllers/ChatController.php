<?php

namespace app\controllers;

use app\engine\App;
use app\models\entities\Access;
use app\models\entities\Chat;


class ChatController extends Controller
{
    protected $layout = 'main';
    protected $defaultAction = 'access';
    protected $render = 'admin/access';

//    public function actionAccess()
//    {
//        echo $this->render($this->render);
//    }


    public function actionGetAll()
    {
        $chat = App::call()->chatRepository->getAll();
        echo json_encode(['result' => $chat ]);
    }

    public function actionGetbyProjid()
    {
        if (isset(App::call()->request->getParams()['proj_id'])) {
            $proj_id= App::call()->request->getParams()['proj_id'];
            $chat = App::call()->chatRepository->getWhere(['proj_id' => $proj_id ]);
        }
        echo json_encode(['result' => $chat ]);
    }

    public function actionSave()
    {
        if (isset(App::call()->request->getParams()['id'])) {
            $id = App::call()->request->getParams()['id'];
            $chat = App::call()->chatRepository->getObject($id);
        } else {
            $chat = New Chat();
        }

        if (isset(App::call()->request->getParams()['proj_id'])) {
            $proj_id = App::call()->request->getParams()['proj_id'];
            $chat->proj_id = $proj_id;
        }

        if (isset(App::call()->request->getParams()['message'])) {
            $message = App::call()->request->getParams()['message'];
            $chat->message = $message;
        }

        if (isset(App::call()->request->getParams()['message'])) {
            $message = App::call()->request->getParams()['message'];
            $chat->message = $message;
        }


        if (isset(App::call()->request->getParams()['sender'])) {
            $sender = App::call()->request->getParams()['sender'];
            $chat->sender = $sender;
        }

        if (isset(App::call()->request->getParams()['viewed'])) {
            $viewed = App::call()->request->getParams()['viewed'];
            $chat->viewed = $viewed;
        }

        if (isset(App::call()->request->getParams()['deleted'])) {
            $deleted = App::call()->request->getParams()['deleted'];
            $chat->deleted = $deleted;
        }


        $result = App::call()->chatRepository->save($chat);
        $array = App::call()->chatRepository->getWhere(['id' => $result]);
        echo json_encode(['result' => $array[0]]);

    }



}
