<?php

namespace app\controllers;
use app\engine\App;
use app\models\entities\Mail;
use app\models\entities\ManagerSites;


class ManagerSitesController extends Controller
{
    public function actionSave()
    {
        if (isset(App::call()->request->getParams()['id'])) {
            $id = App::call()->request->getParams()['id'];
            $managerSites = App::call()->managerSitesRepository->getObject($id);
        } else {
            $managerSites = New ManagerSites();
        }

        if (isset(App::call()->request->getParams()['user_id'])) {
            $user_id = App::call()->request->getParams()['user_id'];
            $managerSites->user_id = $user_id;
        }

        if (isset(App::call()->request->getParams()['site_id'])) {
            $site_id = App::call()->request->getParams()['site_id'];
            $managerSites->site_id = $site_id;
        }

        $result = App::call()->managerSitesRepository->save($managerSites);
        echo $result;
        $user = App::call()->usersRepository->getObject($user_id);
        $fullname = $user->lasttname . ' ' . $user->firstname . ' ' . $user->patronymic;
        $site = App::call()->sitesRepository->getObject($site_id);
        $site_name = $site->site_name;
        $mailSend = new Mail();
        $site_num = $site_id - 1;
        $mailSend->email = 'Regist@i-bios.com';
        $mailSend->subject = "Внимание произошли изменения для сайта \"$site_num\"";
        $mailSend->text_mail = "Внимание! Для сайта \"$site_num\" $site_name добавлен ответственный менеджер : $fullname.";
        $mailSend->send_time = date('Y-m-d H:i:s');
        $mailSend->send = 'NO';
        App::call()->mailRepository->save($mailSend);

        $mailSend_finance = new Mail();
        $mailSend_finance->email = 'Finance@i-bios.com';
        $mailSend_finance->subject = "Внимание произошли изменения для сайта \"$site_num\"";
        $mailSend_finance->text_mail = "Внимание! Для сайта \"$site_num\" $site_name добавлен ответственный менеджер : $fullname.";
        $mailSend_finance->send_time = date('Y-m-d H:i:s');
        $mailSend_finance->send = 'NO';
        App::call()->mailRepository->save($mailSend_finance);

        $mailSend_bd = new Mail();
        $mailSend_bd->email = 'bd@i-bios.com';
        $mailSend_bd->subject = "Внимание произошли изменения для сайта \"$site_num\"";
        $mailSend_bd->text_mail = "Внимание! Для сайта \"$site_num\" $site_name добавлен ответственный менеджер : $fullname.";
        $mailSend_bd->send_time = date('Y-m-d H:i:s');
        $mailSend_bd->send = 'NO';
        App::call()->mailRepository->save($mailSend_bd);





    }

    public function actionGetSiteManagerbyUser()
    {
        if (isset(App::call()->request->getParams()['user_id'])) {
            $user_id = App::call()->request->getParams()['user_id'];
        } else {
            $user_id = App::call()->session->getSession('user_id');
        }

        $data = App::call()->managerSitesRepository->getSiteManagerbyUser($user_id);
        echo json_encode(['result' => $data]);
    }

    public function actionGetQuotableSitesByUser()
    {
        if (isset(App::call()->request->getParams()['user_id'])) {
            $user_id = App::call()->request->getParams()['user_id'];
        } else {
            $user_id = App::call()->session->getSession('user_id');
        }

        $data = App::call()->managerSitesRepository->getQuotableSitesByUser($user_id);
        echo json_encode(['result' => $data]);
    }


    public function actionGetSiteManagerbySites()
    {
        if (isset(App::call()->request->getParams()['site_id'])) {

            $site_id = App::call()->request->getParams()['site_id'];
            $data = App::call()->managerSitesRepository->getSiteManagerbySites($site_id);

            echo json_encode(['result' => $data]);

        } else {
            echo json_encode(['result' => false]);
        }
    }


    public function actionDell()
    {
        $id = App::call()->request->getParams()['id'];

        $managerSites = App::call()->managerSitesRepository->getObject($id);
        $site_id = $managerSites->site_id;
        $user = App::call()->usersRepository->getObject($managerSites->user_id);
        $fullname = $user->lasttname . ' ' . $user->firstname . ' ' . $user->patronymic;
        $site = App::call()->sitesRepository->getObject($site_id);
        $site_name = $site->site_name;
        $site_num = $site_id - 1;
        $mailSend = new Mail();
        $mailSend->email = 'Regist@i-bios.com';
        $mailSend->subject = "Внимание произошли изменения для сайта \"$site_num\"";
        $mailSend->text_mail = "Внимание! Для сайта \"$site_num\" $site_name удален ответственный менеджер : $fullname.";
        $mailSend->send_time = date('Y-m-d H:i:s');
        $mailSend->send = 'NO';
        App::call()->mailRepository->save($mailSend);

        $result = App::call()->managerSitesRepository->delete($managerSites);
        echo json_encode(['result' => $result]);
    }


}


