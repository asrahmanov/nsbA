<?php

namespace app\controllers;

use app\engine\App;
use app\interfaces\IRenderer;
use app\models\entities\OrdersStatusActions;
use app\models\entities\WorksheetsLaboratory;


class LaboratoryController extends Controller
{

    protected $layout = 'admin';
    protected $defaultAction = 'laboratory';
    protected $render = 'laboratory/laboratory';

    public function actionLaboratory()
    {
        echo $this->render($this->render);
    }

    public function actionGetAll()
    {
        $laboratorys = App::call()->worksheetsLaboratoryRepository->getAll();
        echo json_encode(['result' => $laboratorys]);
    }

    public function actionSave()
    {
        $user_id = App::call()->session->getSession('user_id');

        if (isset(App::call()->request->getParams()['proj_id'])) {
            $proj_id = App::call()->request->getParams()['proj_id'];
            $id = App::call()->request->getParams()['id'];
            $laboratory = App::call()->worksheetsLaboratoryRepository->getObject($id);

            if (isset(App::call()->request->getParams()['sample'])) {
                $sample = App::call()->request->getParams()['sample'];
                $laboratory->sample = $sample;

                // Если sample = 1 ДА
                // Нужно измени статус заявки на положительный
                if ($sample == 1) {
                    $order = App::call()->ordersRepository->getObject($proj_id);
                    $order->status_lpo = 14; // Получен ответ из ЛТО
                    App::call()->ordersRepository->save($order);
                    // Записать изменения в лог
                    $log = new OrdersStatusActions();
                    $log->proj_id = $proj_id;
                    $log->department_id = 4;
                    $log->orders_status_id = 14;
                    $log->user_id = $user_id;
                    App::call()->ordersStatusActionsRepository->save($log);

                }
                // Нужно измени статус заявки на отрицательный
                //  sample = 2 НЕТ
                if ($sample == 2) {
                    $order = App::call()->ordersRepository->getObject($proj_id);
                    $order->status_lpo = 35; // ЛТО Невозможно
                    App::call()->ordersRepository->save($order);
                    // Записать изменения в лог
                    $log = new OrdersStatusActions();
                    $log->proj_id = $proj_id;
                    $log->department_id = 4;
                    $log->orders_status_id = 35;
                    $log->user_id = $user_id;
                    App::call()->ordersStatusActionsRepository->save($log);
                }
            }



            if (isset(App::call()->request->getParams()['comments'])) {
                $comments = App::call()->request->getParams()['comments'];
                $laboratory->comments = $comments;
            }

            if (isset(App::call()->request->getParams()['material_intake'])) {
                $material_intake = App::call()->request->getParams()['material_intake'];
                $laboratory->material_intake = $material_intake;
            }

            if (isset(App::call()->request->getParams()['material_intake_text'])) {
                $material_intake_text = App::call()->request->getParams()['material_intake_text'];
                $laboratory->material_intake_text = $material_intake_text;
            }

            if (isset(App::call()->request->getParams()['material_intake_num'])) {
                $material_intake_num = App::call()->request->getParams()['material_intake_num'];
                $laboratory->material_intake_num = $material_intake_num;
            }

            if (isset(App::call()->request->getParams()['expendable_materials'])) {
                $expendable_materials = App::call()->request->getParams()['expendable_materials'];
                $laboratory->expendable_materials = $expendable_materials;
            }

            if (isset(App::call()->request->getParams()['expendable_materials_text'])) {
                $expendable_materials_text = App::call()->request->getParams()['expendable_materials_text'];
                $laboratory->expendable_materials_text = $expendable_materials_text;
            }

            if (isset(App::call()->request->getParams()['expendable_materials_num'])) {
                $expendable_materials_num = App::call()->request->getParams()['expendable_materials_num'];
                $laboratory->expendable_materials_num = $expendable_materials_num;
            }

            if (isset(App::call()->request->getParams()['financial_expenses'])) {
                $financial_expenses = App::call()->request->getParams()['financial_expenses'];
                $laboratory->financial_expenses = $financial_expenses;
            }

            if (isset(App::call()->request->getParams()['financial_expenses_text'])) {
                $financial_expenses_text = App::call()->request->getParams()['financial_expenses_text'];
                $laboratory->financial_expenses_text = $financial_expenses_text;
            }

            if (isset(App::call()->request->getParams()['financial_expenses_num'])) {
                $financial_expenses_num = App::call()->request->getParams()['financial_expenses_num'];
                $laboratory->financial_expenses_num = $financial_expenses_num;
            }

            $laboratory->user_id = $user_id;
            $laboratory->created_at = DATE('Y-m-d H:i:s');
            $result = App::call()->worksheetsLaboratoryRepository->save($laboratory);
        }

        echo $result;

    }


}
