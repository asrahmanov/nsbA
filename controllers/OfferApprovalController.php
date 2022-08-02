<?php

namespace app\controllers;

use app\engine\App;
use app\models\entities\Mail;
use app\models\entities\OfferApproval;
use app\models\entities\OrdersStatusActions;

class OfferApprovalController extends Controller
{

    protected $layout = 'main';
    protected $folder = 'OfferApproval/';
    protected $defaultAction = 'OfferApproval';

    public function actionOfferApproval()
    {
        $user_id = App::call()->session->getSession('user_id');
        $offer_approvals_waiting = App::call()->offerApprovalRepository->getApproval($user_id);
        echo $this->render($this->folder . $this->defaultAction, [
            'offer_approvals_waiting' => $offer_approvals_waiting
        ]);
    }

    public function actionSave()
    {
        if (isset(App::call()->request->getParams()['id'])) {
            $id = App::call()->request->getParams()['id'];
            $item = App::call()->offerApprovalRepository->getObject($id);
        } else {
            $item = New OfferApproval();
            $user = App::call()->usersRepository->getOne(1); // Капустин
            $email = $user['email'];
            $mailSend = new Mail();
            if (($email !== '') && !is_null($email)) {
                $mailSend->email = $email;
                $mailSend->subject = "Запрос на одобрение";
                $mailSend->text_mail = "http://crm.i-bios.com/orders/info/?idFR={$item->id}";
                $mailSend->send_time = date('Y-m-d H:i:s');
                $mailSend->send = 'NO';
            }
//            App::call()->mailRepository->save($mailSend);
        }

        if (isset(App::call()->request->getParams()['offer_id'])) {
            $offer_id = App::call()->request->getParams()['offer_id'];
            $item->offer_id = $offer_id;
            if (isset(App::call()->request->getParams()['user_id'])) {
                $user_id = App::call()->request->getParams()['user_id'];
                if ($user_id === '1') {
                    $log = new OrdersStatusActions();
                    $log->proj_id = $offer_id;
                    $log->department_id = 6;
                    $log->user_id = App::call()->session->getSession('user_id');
                    $order = App::call()->ordersRepository->getObject($offer_id);
                    if (!isset(App::call()->request->getParams()['approved'])) {
                        $order->status_boss = 40;
                        $log->orders_status_id = 40;
                    } else {
                        $approved = App::call()->request->getParams()['approved'];
                        if ($approved === '1') {
                            $order->status_boss = 41;
                            $log->orders_status_id = 41;

                        } else {
                            $order->status_boss = 23;
                            $log->orders_status_id = 23;
                        }
                    }
                    App::call()->ordersRepository->save($order);
                    App::call()->ordersStatusActionsRepository->save($log);
                } else if ($user_id === '56') {
                    if (isset(App::call()->request->getParams()['approved'])) {
                        $log = new OrdersStatusActions();
                        $log->proj_id = $offer_id;
                        $log->department_id = 6;
                        $log->user_id = App::call()->session->getSession('user_id');
                        $order = App::call()->ordersRepository->getObject($offer_id);
                        $approved = App::call()->request->getParams()['approved'];
                        if ($approved === '1') {
                            $order->status_boss = 24;
                            $log->orders_status_id = 24;

                        } else {
                            $order->status_boss = 23;
                            $log->orders_status_id = 23;
                        }
                        App::call()->ordersRepository->save($order);
                        App::call()->ordersStatusActionsRepository->save($log);
                    }
                }
            }
        }
        if (isset(App::call()->request->getParams()['user_id'])) {
            $user_id = App::call()->request->getParams()['user_id'];
            $item->user_id = $user_id;
        }
        if (isset(App::call()->request->getParams()['approved'])) {
            $approved = App::call()->request->getParams()['approved'];
            $item->approved = $approved;
        }
        if (isset(App::call()->request->getParams()['comment'])) {
            $comment = App::call()->request->getParams()['comment'];
            $item->comment = $comment;
        }

        $result = App::call()->offerApprovalRepository->save($item);

        echo $result;

        if (isset($approved) && isset($user_id) && $approved === '1' && $user_id === '1') {
            $approvers = [/*4, 5, 56*/]; // Пруцкий, Гранстрем, Анисимов
            foreach ($approvers as $approver_id) {
                $user1 = App::call()->usersRepository->getOne($approver_id);
                $email = $user1['email'];
                $mailSend = new Mail();
                if (($email !== '') && !is_null($email)) {
                    $mailSend->email = $email;
                    $mailSend->subject = "Запрос на одобрение";
                    $mailSend->text_mail = "http://crm.i-bios.com/orders/info/?idFR={$item['id']}";
                    $mailSend->send_time = date('Y-m-d H:i:s');
                    $mailSend->send = 'NO';
                }
//                App::call()->mailRepository->save($mailSend);
            }
        }
    }

}


