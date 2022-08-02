<?php

namespace app\controllers;

use app\engine\App;
use app\models\entities\Orders;


class DashboardController extends Controller
{

    protected $layout = 'admin';
    protected $defaultAction = 'dashboard';
    protected $render = 'orders/dashboard';


    public function actionDashboard()
    {
        $user_id = App::call()->session->getSession('user_id');
        $role_id = App::call()->session->getSession('role_id');
        echo $this->render($this->render, [
            'user_id' => $user_id,
            'role_id' => $role_id
        ]);
    }


    public function actiongetAllbyDate() {
        $frDateOne = App::call()->request->getParams()['frDateOne'];
        $frDateTwo = App::call()->request->getParams()['frDateTwo'];
        if($frDateOne == '' ) {
            $frDateOne = Date('Y').'-01-01';
        }
        if($frDateTwo == '' ) {
            $frDateTwo = Date('Y').'-12-31';
        }
        $orders = App::call()->ordersRepository->getAllbyDateDASHBORD($frDateOne,$frDateTwo);
        echo json_encode(['result' => $orders]);

    }

    public function actiongetAllbyDateCompanyPriority() {
        $frDateOne = App::call()->request->getParams()['frDateOne'];
        $frDateTwo = App::call()->request->getParams()['frDateTwo'];
        if($frDateOne == '' ) {
            $frDateOne = Date('Y').'-01-01';
        }
        if($frDateTwo == '' ) {
            $frDateTwo = Date('Y').'-12-31';
        }
        $orders = App::call()->ordersRepository->getAllbyDateDASHBORDCompanyPriority($frDateOne,$frDateTwo);
        echo json_encode(['result' => $orders]);

    }

    public function actionStatic() {

        $frDateOne = '2020-01-01';
        $orders = App::call()->ordersRepository->getStaticAll($frDateOne);

        for ($i = 0; $i < count($orders); $i++) {
            $all[$orders[$i]['id']]['all'] = $orders[$i]['total'];
            $all[$orders[$i]['id']]['fio'] = $orders[$i]['fio'];
            $all[$orders[$i]['id']]['user_id'] = $orders[$i]['id'];
        }

        $ordersGood = App::call()->ordersRepository->getStaticGood($frDateOne);
        for ($j = 0; $j < count($ordersGood); $j++) {
            $all[$ordersGood[$j]['id']]['good'] = $ordersGood[$j]['total'];
            $all[$ordersGood[$j]['id']]['fio'] = $ordersGood[$j]['fio'];
            $all[$ordersGood[$j]['id']]['user_id'] = $ordersGood[$j]['id'];
        }

        echo json_encode(['result' => $all]);

    }

    public function actionGetOneDashbord() {
        $proj_id = App::call()->request->getParams()['proj_id'];
        $order = App::call()->ordersRepository->getAllbyDateDASHBORDOne($proj_id);
        echo json_encode(['result' => $order]);

    }

    public function actionGetNotStatus() {
        $orders = App::call()->ordersRepository->getNotStatus();
        echo json_encode(['result' => $orders]);
    }


}
