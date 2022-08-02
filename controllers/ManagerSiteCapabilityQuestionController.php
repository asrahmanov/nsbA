<?php

namespace app\controllers;

use app\engine\App;
use app\models\entities\ManagerSiteCapabilityQuestion;

class ManagerSiteCapabilityQuestionController extends Controller
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
        $manager_site_capability_id = App::call()->request->getParams()['manager_site_capability_id'];
        $site_id = App::call()->request->getParams()['site_id'];
        $array = App::call()->managerSiteCapabilityQuestionRepository->getWhere([
            'template_id' => $template_id,
            'manager_site_capability_id' => $manager_site_capability_id,
            'site_id' => $site_id
        ]);
        echo json_encode($array);
    }
    public function actionGetAllAdmin()
    {
        $template_id = App::call()->request->getParams()['template_id'];
        $site_id = App::call()->request->getParams()['site_id'];
        $user_id = App::call()->request->getParams()['user_id'];

        $array = App::call()->managerSiteCapabilityQuestionRepository->getWhere([
            'template_id' => $template_id,
            'site_id' => $site_id,
            'user_id' => $user_id
        ]);
        echo json_encode($array);
    }


    public function actiongetByTemplate()
    {
        $template_id = App::call()->request->getParams()['template_id'];
        $array = App::call()->managerSiteCapabilityQuestionRepository->getWhere([
            'template_id' => $template_id
        ]);
        echo json_encode($array);
    }

    public function actionSave()
    {
        if (isset(App::call()->request->getParams()['id'])) {
            $id = App::call()->request->getParams()['id'];
            $question = App::call()->managerSiteCapabilityQuestionRepository->getObject($id);
        } else {
            $question = New ManagerSiteCapabilityQuestion();
        }

        if (isset(App::call()->request->getParams()['template_id'])) {
            $template_id = App::call()->request->getParams()['template_id'];
            $question->template_id = $template_id;
        }


         if (isset(App::call()->request->getParams()['manager_site_capability_id'])) {
            $manager_site_capability_id = App::call()->request->getParams()['manager_site_capability_id'];
            $question->manager_site_capability_id = $manager_site_capability_id;
         }

         if (isset(App::call()->request->getParams()['question_id'])) {
            $question_id = App::call()->request->getParams()['question_id'];
            $question->question_id = $question_id;
         }

         if (isset(App::call()->request->getParams()['site_id'])) {
            $site_id = App::call()->request->getParams()['site_id'];
            $question->site_id = $site_id;
         }


        if (isset(App::call()->request->getParams()['question'])) {
            $questions = App::call()->request->getParams()['question'];
            $question->question = $questions;
        }

        if (isset(App::call()->request->getParams()['user_id'])) {
            $questions = App::call()->request->getParams()['user_id'];
            $question->user_id = $questions;
        }


        if (isset(App::call()->request->getParams()['deleted'])) {
            $deleted = App::call()->request->getParams()['deleted'];
            $question->deleted = $deleted;
        }

        $question->created_at = DATE('Y-m-d H:m:i');

        $result = App::call()->managerSiteCapabilityQuestionRepository->save($question);

        echo json_encode(['result' => $result]);
    }


    public function actionDell()
    {
        $id = App::call()->request->getParams()['id'];
        $question = App::call()->managerSiteCapabilityQuestionRepository->getObject($id);
        $result = App::call()->managerSiteCapabilityQuestionRepository->delete($question);
        echo json_encode(['result' => $result]);
    }


}


