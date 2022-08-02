<?php

namespace app\controllers;

use app\engine\App;
use app\models\entities\Mail;
use app\models\entities\ManagerSitecapability;
use app\models\entities\ManagerSiteCapabilityAnswer;


class ManagerSitecapabilityController extends Controller
{
    protected $layout = 'admin';
    protected $defaultAction = 'siteCapability';
    protected $render = 'siteCapability/manager';

    public function actionSiteCapability()
    {
        echo $this->render($this->render);
    }

    public function actionManagerEdit()
    {
        $id = App::call()->request->getParams()['id'];
        $managerList = App::call()->managerSitecapabilityRepository->getByUserAndTemplate($id);
        $template_id = $managerList['template_id'];
        $site_name = $managerList['site_name'];
        $site_id = $managerList['site_id'];
        $template_name = $managerList['template_name'];
        $created_at = $managerList['created_at'];
        $user_id = App::call()->session->getSession('user_id');

        if ($managerList !== 0) {
            echo $this->render('siteCapability/managerEdit', [
                'template_id' => $template_id,
                'site_name' => $site_name,
                'template_name' => $template_name,
                'created_at' => $created_at,
                'manager_site_capability_id' => $id,
                'site_id' => $site_id,
                'user_id' => $user_id
            ]);
        } else {
            echo $this->renderTemplate('404', $params = [
                'error' => 'Это не ваша задача'
            ]);
        }


    }

    public function actionGetAllByStatus()
    {

        $template_id = App::call()->request->getParams()['template_id'];
        $diseases_val = App::call()->request->getParams()['diseases_val'];
        $tissues_val = App::call()->request->getParams()['tissues_val'];
        $array = App::call()->managerSitecapabilityRepository->getAllByStatusAndtemplate(2,$template_id,$diseases_val,$tissues_val);
        echo json_encode($array);
    }




    public function actionGenerateSitecapability()
    {
        $template_id = App::call()->request->getParams()['id'];
        $sitesUsers = App::call()->usersRepository->getUserJoinSites(3);
//        $tissues = App::call()->tissuesSitecapabilityRepository->getWhere(['template_id' => $template_id]);
//        $disease = App::call()->diseasesSitecapabilityRepository->getWhere(['template_id' => $template_id]);
        $result = [];
        for ($i = 0, $iMax = count($sitesUsers); $i < $iMax; $i++) {
            // Создаем глобалбную задачу для каждого менеджера и сайта
            $work = new ManagerSitecapability();
            $work->template_id = $template_id;
            $work->user_id = $sitesUsers[$i]['user_id'];
            $work->site_id = $sitesUsers[$i]['site_id'];
            $work->status_id = 1;
            $work->update_at = date('Y-m-d H:i:s');
            $work->create_date = DATE('Y-m-d');
            $result[] = App::call()->managerSitecapabilityRepository->save($work);
        }
        // Считаем сколько создали, а сколько нет
        $good = 0;
        $bad = 0;
        foreach ($result as $value) {
            if ($value === '0') {
                $bad++;
            } else {
                $good++;
            }
        }




        if($bad == 0) {
            // Рассылаем письма счастья
            $userMail = App::call()->usersRepository->getManager();

            for ($j = 0, $jMax = count($userMail); $j < $jMax; $j++) {
                $email = $userMail[$j]['email'];
                $subject = 'Site Capability';
                $text = 'Добрый день, Вам необходимо заполнить опросники по всем Вашим сайтам.';
                $mailSend = new Mail();
                if(($email !== '') && !is_null($email)) {
                    $mailSend->email = $email;
                    $mailSend->subject = $subject;
                    $mailSend->text_mail = $text;
                    $mailSend->send_time = date('Y-m-d H:i:s');
                    $mailSend->send = 'NO';
                    App::call()->mailRepository->save($mailSend);
                }
            }
        }



        echo json_encode([
            'bad' => $bad,
            'good' => $good
        ]);

    }



    public function actionGenerateSitecapabilityByDepartament()
    {
        $template_id = App::call()->request->getParams()['id'];
        $sitesUsers = App::call()->usersRepository->getUserJoinSitesByDepart();

        $result = [];
        for ($i = 0, $iMax = count($sitesUsers); $i < $iMax; $i++) {
            // Создаем глобалбную задачу для каждого менеджера и сайта
            $work = new ManagerSitecapability();
            $work->template_id = $template_id;
            $work->user_id = $sitesUsers[$i]['user_id'];
            $work->site_id = $sitesUsers[$i]['site_id'];
            $work->status_id = 1;
            $work->update_at = date('Y-m-d H:i:s');
            $work->create_date = DATE('Y-m-d');
            $result[] = App::call()->managerSitecapabilityRepository->save($work);
        }
        // Считаем сколько создали, а сколько нет
        $good = 0;
        $bad = 0;
        foreach ($result as $value) {
            if ($value === '0') {
                $bad++;
            } else {
                $good++;
            }
        }


        echo json_encode([
            'bad' => $bad,
            'good' => $good,
            'data' => $sitesUsers
        ]);

    }


    public function actionGenerateSitecapabilityBuUsers()
    {
        $template_id = App::call()->request->getParams()['id'];
        $user_id = App::call()->request->getParams()['user_id'];
        $sitesUsers = App::call()->usersRepository->getUserJoinSitesByUserId($user_id);

        $result = [];
        for ($i = 0, $iMax = count($sitesUsers); $i < $iMax; $i++) {
            // Создаем глобалбную задачу для каждого менеджера и сайта
            $work = new ManagerSitecapability();
            $work->template_id = $template_id;
            $work->user_id = $sitesUsers[$i]['user_id'];
            $work->site_id = $sitesUsers[$i]['site_id'];
            $work->status_id = 1;
            $work->update_at = date('Y-m-d H:i:s');
            $work->create_date = DATE('Y-m-d');
            $result[] = App::call()->managerSitecapabilityRepository->save($work);
        }




        App::call()->mailRepository->save($mailSend);
        // Считаем сколько создали, а сколько нет
        $good = 0;
        $bad = 0;
        foreach ($result as $value) {
            if ($value === '0') {
                $bad++;
            } else {
                $good++;
            }
        }

        echo json_encode([
            'bad' => $bad,
            'good' => $good
        ]);

    }


    public function actionGetCapabilitybyUsers()
    {
        $user_id = App::call()->session->getSession('user_id');
        $capability = App::call()->managerSitecapabilityRepository->getByUser($user_id);
        echo json_encode(['capability' => $capability]);

    }

    public function actionSaveAnswer()
    {


        if (isset(App::call()->request->getParams()['id'])) {
            $id = App::call()->request->getParams()['id'];
            $answer = App::call()->managerSiteCapabilityAnswerRepository->getObject($id);

        } else {
            $answer = New ManagerSiteCapabilityAnswer();
        }


        if (isset(App::call()->request->getParams()['manager_site_capability_id'])) {
            $manager_site_capability_id = App::call()->request->getParams()['manager_site_capability_id'];
            $answer->manager_site_capability_id = $manager_site_capability_id;
        }

        if (isset(App::call()->request->getParams()['tissues_sitecapability_id'])) {
            $tissues_id = App::call()->request->getParams()['tissues_sitecapability_id'];
            $answer->tissues_sitecapability_id = $tissues_id;
        }

        if (isset(App::call()->request->getParams()['diseases_sitecapability_id'])) {
            $diseases_id = App::call()->request->getParams()['diseases_sitecapability_id'];
            $answer->diseases_sitecapability_id = $diseases_id;
        }

        if (isset(App::call()->request->getParams()['answer'])) {
            $answerValue = App::call()->request->getParams()['answer'];
            $answer->answer = $answerValue;
        }
        $answer->created_at = Date('Y-m-d H:m:i');
        $result = App::call()->managerSiteCapabilityAnswerRepository->save($answer);
        echo json_encode(['result' => $result]);

    }


    public function actionGetMyAnser()
    {
        $manager_site_capability_id = App::call()->request->getParams()['manager_site_capability_id'];
        $answer = App::call()->managerSiteCapabilityAnswerRepository->getWhere(['manager_site_capability_id' => $manager_site_capability_id]);
        echo json_encode(['answer' => $answer]);

    }


    public function actionGetMyAnserHistory()
    {
//        $site_id = App::call()->request->getParams()['site_id'];
//
//        $answer = App::call()->managerSiteCapabilityAnswerRepository->getByUser($user_id);
//        echo json_encode(['answer' => $answer]);


    }


    public function actionSave()
    {
        if(isset(App::call()->request->getParams()['id'])){
            $id = App::call()->request->getParams()['id'];
            $status_id = App::call()->request->getParams()['status_id'];
            $work = App::call()->managerSitecapabilityRepository->getObject($id);
            $work->status_id = $status_id;
            $result = App::call()->managerSitecapabilityRepository->save($work);
            echo json_encode(['result' => $result]);

        }


    }

    public function actionGetForReport () {
        $array = App::call()->managerSitecapabilityRepository->getForReport();
        echo json_encode($array);
    }
}
