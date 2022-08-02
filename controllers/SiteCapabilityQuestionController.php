<?php

namespace app\controllers;

use app\engine\App;
use app\models\entities\SiteCapabilityQuestion;


class SiteCapabilityQuestionController extends Controller
{

    protected $layout = 'vue';
    protected $folder = 'question/';
    protected $defaultAction = 'question';


    public function actionQuote()
    {

        echo 'Упс';

    }

    public function actionGetAll()
    {
        $template_id = App::call()->request->getParams()['template_id'];
        $array = App::call()->siteCapabilityQuestionRepository->getWhere([
            'template_id' => $template_id
        ]);
        echo json_encode($array);
    }

    public function actionSave()
    {
        if (isset(App::call()->request->getParams()['id'])) {
            $id = App::call()->request->getParams()['id'];
            $question = App::call()->siteCapabilityQuestionRepository->getObject($id);
        } else {
            $question = New SiteCapabilityQuestion();
        }

        if (isset(App::call()->request->getParams()['template_id'])) {
            $template_id = App::call()->request->getParams()['template_id'];
            $question->template_id = $template_id;
        }


        if (isset(App::call()->request->getParams()['question'])) {
            $questions = App::call()->request->getParams()['question'];
            $question->question = $questions;
        }

        if (isset(App::call()->request->getParams()['users_group'])) {
            $users_group = App::call()->request->getParams()['users_group'];
            $question->users_group = $users_group;
        }

        if (isset(App::call()->request->getParams()['deleted'])) {
            $deleted = App::call()->request->getParams()['deleted'];
            $question->deleted = $deleted;
        }

        $question->created_at = DATE('Y-m-d H:m:i');

        $result = App::call()->siteCapabilityQuestionRepository->save($question);

        echo json_encode(['result' => $result]);
    }


    public function actionDell()
    {
        $id = App::call()->request->getParams()['id'];
        $question = App::call()->siteCapabilityQuestionRepository->getObject($id);
        $result = App::call()->siteCapabilityQuestionRepository->delete($question);
        echo json_encode(['result' => $result]);
    }


}


