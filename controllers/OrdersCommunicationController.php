<?php

namespace app\controllers;

use app\engine\App;
use app\models\entities\Orders;


class OrdersCommunicationController extends Controller
{

    protected $layout = 'admin';
    protected $defaultAction = 'communication';
    protected $render = 'orders/communication';


    public function actionCommunication()
    {
        $user_id = App::call()->session->getSession('user_id');
        $role_id = App::call()->session->getSession('role_id');
        echo $this->render($this->render, [
            'user_id' => $user_id,
            'role_id' => $role_id
        ]);
    }


    public function actionGetCommunication()
    {
        $nowDay = Date('Y-m-d');

        if (isset(App::call()->request->getParams()['day'])) {
            $day = App::call()->request->getParams()['day'];
            if ($day == 'Future') {
                $nowDay = Date('2038-01-01');
            }
        }

        $orders = App::call()->ordersRepository->GetCommunicationNow($nowDay);
        $statusLog = App::call()->ordersRepository->GetCommunicationStatusLog($nowDay);
        $managerSubmit = App::call()->ordersRepository->GetCommunicationManager($nowDay);
        $staff = App::call()->ordersRepository->GetCommunicationStaf($nowDay);
        echo json_encode([
            'orders' => $orders,
            'statusLog' => $statusLog,
            'managerSubmit' => $managerSubmit,
            'staff' => $staff
        ]);

    }


}
