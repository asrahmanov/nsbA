<?php

namespace app\controllers;
use app\engine\App;
use app\models\entities\CompaniesContacts;
use app\models\entities\Vacation;

class VacationController extends Controller
{

    protected $layout = 'admin';
    protected $folder = 'vacation/';
    protected $defaultAction = 'vacation';

    public function actionVacation()
    {
        echo $this->render($this->folder . $this->defaultAction);
    }


    public function actionGetAll()
    {
        $array = App::call()->vacationRepository->getAll();
        echo json_encode(['result' => $array]);
    }


    public function actionGetByUserId()
    {
        $user_id = App::call()->request->getParams()['user_id'];
        $array = App::call()->vacationRepository->getWhere(['user_id' => $user_id]);
        echo json_encode(['result' => $array]);
    }

    public function actionGetMy()
    {
        $user_id = App::call()->session->getSession('user_id');
        $array = App::call()->vacationRepository->getWhere(['user_id' => $user_id]);
        echo json_encode(['result' => $array]);
    }

    public function actionSave()
    {
        if (isset(App::call()->request->getParams()['id'])) {
            $id = App::call()->request->getParams()['id'];
            $vacation = App::call()->vacationRepository->getObject($id);
        } else {
            $vacation = New Vacation();
        }


            $user_id = App::call()->session->getSession('user_id');
            $vacation->user_id = $user_id;



        if (isset(App::call()->request->getParams()['date_start'])) {
            $date_start = App::call()->request->getParams()['date_start'];
            $vacation->date_start = $date_start;
        }

        if (isset(App::call()->request->getParams()['date_end'])) {
            $date_end = App::call()->request->getParams()['date_end'];
            $vacation->date_end = $date_end;
        }

        if (isset(App::call()->request->getParams()['comment'])) {
            $comment = App::call()->request->getParams()['comment'];
            $vacation->comment = $comment;
        }

        $vacation->created_at = DATE('Y-m-d H:i:s');

        if (isset(App::call()->request->getParams()['deleted'])) {
            $deleted = App::call()->request->getParams()['deleted'];
            $vacation->deleted = $deleted;
        }


        $result = App::call()->vacationRepository->save($vacation);

        echo json_encode(['result' => $result]);

    }


}
