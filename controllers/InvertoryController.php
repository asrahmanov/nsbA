<?php

namespace app\controllers;

use app\engine\App;
use app\interfaces\IRenderer;
use app\models\entities\OrdersStatusActions;
use app\models\entities\WorksheetsInvertory;


class InvertoryController extends Controller
{

    protected $layout = 'admin';
    protected $defaultAction = 'invertory';
    protected $render = 'invertory/invertory';

    public function actionInvertory()
    {
        echo $this->render($this->render);
    }

    public function actionGetAll()
    {
        $invertorys = App::call()->worksheetsInvertoryRepository->getAll();
        echo json_encode(['result' => $invertorys]);
    }

    public function actionSave()
    {
        $user_id = App::call()->session->getSession('user_id');

        if (isset(App::call()->request->getParams()['proj_id'])) {
            $proj_id = App::call()->request->getParams()['proj_id'];
            $id = App::call()->request->getParams()['id'];
            $invertory = App::call()->worksheetsInvertoryRepository->getObject($id);

            if (isset(App::call()->request->getParams()['sample'])) {
                $sample = App::call()->request->getParams()['sample'];
                $invertory->sample = $sample;

                // Если sample = 1
                //  Да
                // Нужно измени статус заявки
                if($sample == 1) {
                    $order = App::call()->ordersRepository->getObject($proj_id);
                    $order->status_project = 9; // Получен инвентори
                    App::call()->ordersRepository->save($order);

                    $log = new OrdersStatusActions() ;
                    $log->proj_id = $proj_id;
                    $log->department_id = 3;
                    $log->orders_status_id = 9;
                    $log->user_id = $user_id;
                    App::call()->ordersStatusActionsRepository->save($log);

                }
                if($sample == 2) {
                    $order = App::call()->ordersRepository->getObject($proj_id);
                    $order->status_project = 34; // Инвентари отсутсвует
                    App::call()->ordersRepository->save($order);

                    $log = new OrdersStatusActions();
                    $log->proj_id = $proj_id;
                    $log->department_id = 3;
                    $log->orders_status_id = 34;
                    $log->user_id = $user_id;
                    App::call()->ordersStatusActionsRepository->save($log);

                }
            }

            if (isset(App::call()->request->getParams()['alias'])) {
                $alias = App::call()->request->getParams()['alias'];
                $invertory->alias = $alias;
            }

            if (isset(App::call()->request->getParams()['comments'])) {
                $comments = App::call()->request->getParams()['comments'];
                $invertory->comments = $comments;
            }

            $invertory->user_id = $user_id;
            $invertory->created_at = DATE('Y-m-d H:i:s');
            $result = App::call()->worksheetsInvertoryRepository->save($invertory);
        }

     echo $result;

    }


}
