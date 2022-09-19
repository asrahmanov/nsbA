<?php

namespace app\controllers;

use app\engine\App;
use app\models\entities\ClinicalCaseDraft;
use app\models\entities\Mail;

class ClinicalCaseDraftController extends Controller
{

    protected $defaultAction = 'ClinicalCaseDraft';

    public function actionClinicalCaseDraft()
    {
        $user_id = App::call()->session->getSession('user_id');
        $array = App::call()->clinicalCaseDraftRepository->getAllDrafts($user_id);
        echo json_encode($array);
    }

    public function actionSave()
    {
        if (isset(App::call()->request->getParams()['id'])) {
            $id = App::call()->request->getParams()['id'];
            $site = App::call()->clinicalCaseDraftRepository->getObject($id);

        } else {
            $site = New ClinicalCaseDraft();
        }

        if (isset(App::call()->request->getParams()['clinical_case_id'])) {
            $clinical_case_id = App::call()->request->getParams()['clinical_case_id'];
            $site->clinical_case_id = $clinical_case_id;
        }
        if (isset(App::call()->request->getParams()['user_id'])) {
            $user_id = App::call()->request->getParams()['user_id'];
            $site->user_id = $user_id;
        }

        $result = App::call()->clinicalCaseDraftRepository->save($site);

        echo json_encode(intval($result));

    }

    public function actionDelete()
    {
        $id = App::call()->request->getParams()['id'];
        $clinicalCaseDraft = App::call()->clinicalCaseDraftRepository->getObject($id);
        $result = App::call()->clinicalCaseDraftRepository->delete($clinicalCaseDraft);
        echo json_encode(['result' => $result]);
    }


    public function actionSendMail()
    {
        $user_id = App::call()->session->getSession('user_id');
        $draft = App::call()->clinicalCaseDraftRepository->getWhere(['user_id' => $user_id]);
        $managers_all = [];
//        var_dump($draft);
        for ($i = 0; $i < count($draft); $i++) {
            $clinicalCase = App::call()->clinicalCaseRepository->getObject($draft[$i]['clinical_case_id']);
            if (($clinicalCase->site_id !== '') && !is_null($clinicalCase->site_id)) {
                $site_id = $clinicalCase->site_id + 1;
                $site = App::call()->sitesRepository->getObject($site_id);
                $site_name = $site->site_name;
                $site_managers = App::call()->managerSitesRepository->getSiteManagerbySites($site_id);
                foreach ($site_managers as $manager) {
                    $managers_all[$manager['id_user']][] = [
                        'project_id' => $clinicalCase->project_id,
                        'cid' => $clinicalCase->clinical_case_id,
                        'clinical_date' => $clinicalCase->clinical_date,
                        'site_name' => $site_name
                    ];
                    $clinicalCase->send_count = $clinicalCase->send_count + 1;
                    $clinicalCase->last_send = DATE('Y-m-d H:i:s');
                    App::call()->clinicalCaseRepository->save($clinicalCase);
                }
            }
        }

        foreach ($managers_all as $manager_id => $manager_item) {
            $manager = App::call()->usersRepository->getObject($manager_id);
            $text = "";
            $text .= "Добрый день, {$manager->lasttname} {$manager->firstname}. <br>";
            $text .= "Просьба  в срочном порядке выслать клиническую информацию по:. <br>";
            $subject = "CRF ";
            $email = $manager->email;
            foreach ($manager_item as $item) {
                $project_id = $item['project_id'];
                $cid = $item['cid'];
                $clinical_date = $item['clinical_date'];
                $site_name = $item['site_name'];
                $subject .= $cid . " ";
                $text .= "
                    Project ID: $project_id<br> 
                    Site: $site_name<br> 
                    $cid <br>
                    дата регистрации: $clinical_date
                    <br><br>";
            }
            $mailSend = new Mail();
            if (($email !== '') && !is_null($email)) {
                $subject = mb_strimwidth($subject, 0, 100, "...");
                $mailSend->email = $email;
                $mailSend->subject = $subject;
                $mailSend->text_mail = $text;
                $mailSend->send_time = date('Y-m-d H:i:s');
                $mailSend->send = 'NO';
                $mailSend->reply_to = 'crf@i-bios.com';
                App::call()->mailRepository->save($mailSend);
                $mailSend->reply_to = $email;
                $mailSend->email = 'crf@i-bios.com';
                $result = App::call()->mailRepository->save($mailSend);
                echo $result;
            }
            App::call()->clinicalCaseDraftRepository->deleteDraftsByUserId($user_id);
        }

//        foreach ($managers_all as  $manager_id => $manager_item) {
//            foreach ($manager_item as$params) {
//                $text = "";
//                $subject = "CRF ";
//                $manager = App::call()->usersRepository->getObject($manager_id);
//                $email = $manager->email;
//                //$email = 'maksim.zvyagintsev@nbioservice.com';
//                $text .= "Добрый день, {$manager->lasttname} {$manager->firstname}. <br>";
//                $text .= "Просьба  в срочном порядке выслать клиническую информацию по:. <br>";
//                foreach ($params as $info) {
//                    $project_id = $info->project_id;
//                    $cid = $info->cid;
//                    $clinical_date = $info->clinical_date;
//                    $site_name = $info->site_name;
//                    $subject .= $cid . " ";
//                    $text .= "
//                Project ID: $project_id<br>
//                Site: $site_name<br>
//                $cid <br>
//                дата регистрации: $clinical_date
//                <br><br>";
//                }
//                $mailSend = new Mail();
//                if (($email !== '') && !is_null($email)) {
//                    $mailSend->email = $email;
//                    $mailSend->subject = $subject;
//                    $mailSend->text_mail = $text;
//                    $mailSend->send_time = date('Y-m-d H:i:s');
//                    $mailSend->send = 'NO';
//                    $mailSend->reply_to = 'crf@i-bios.com';
//                    App::call()->mailRepository->save($mailSend);
//                    $mailSend->reply_to = $email;
//                    $mailSend->email = 'crf@i-bios.com';
//                    App::call()->mailRepository->save($mailSend);
//                }
//                App::call()->clinicalCaseDraftRepository->deleteDraftsByUserId($user_id);
//            }
//        }

        //echo json_encode(['result' => $manager]);
    }

}
