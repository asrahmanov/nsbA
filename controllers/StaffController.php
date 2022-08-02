<?php

namespace app\controllers;

use app\engine\App;
use app\models\entities\CompanyStaff;


class StaffController extends Controller
{
    protected $layout = 'admin';
    protected $folder = 'staff/';
    protected $defaultAction = 'staff';

    public function actionStaff()
    {
        echo $this->render($this->defaultAction);
    }

    public function actionGetByCompany()
    {
        $script_id = App::call()->request->getParams()['script_id'];
        $array = App::call()->companyStaffRepository->getByCompany($script_id);
        echo json_encode(['result' => $array]);

    }

    public function actionGetAll()
    {
        $array = App::call()->companyStaffRepository->getAllnotDeleted();
        echo json_encode(['result' => $array]);
    }

    public function actionSave()
    {
        $staff = New CompanyStaff();
        if (App::call()->request->getParams()['script_id']) {
            $script_id = App::call()->request->getParams()['script_id'];
            $staff->script_id = $script_id;
        }
        if (App::call()->request->getParams()['name']) {
            $name = App::call()->request->getParams()['name'];
            $staff->name = $name;
        }
        if (App::call()->request->getParams()['position']) {
            $position = App::call()->request->getParams()['position'];
            $staff->position = $position;
        }
        if (App::call()->request->getParams()['email']) {
            $email = App::call()->request->getParams()['email'];
            $staff->email = $email;
        }
        if (App::call()->request->getParams()['phone']) {
            $phone = App::call()->request->getParams()['phone'];
            $staff->phone = $phone;
        }

        $result = App::call()->companyStaffRepository->save($staff);
        echo json_encode(['result' => $result]);
    }


    public function actionDell()
    {
        $id = App::call()->request->getParams()['id'];
        $staff = App::call()->companyStaffRepository->getObject($id);
        $staff->deleted = 1;
        //$result = App::call()->companyStaffRepository->delete($staff)
        $result = App::call()->companyStaffRepository->save($staff);;
        echo json_encode(['result' => $result ]);
    }

}
