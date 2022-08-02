<?php

namespace app\controllers;

use app\engine\App;
use app\models\entities\CompaniesContactsSpecial;


class CompaniesContactsSpecialController extends Controller
{

    public function actionGetAll()
    {
        $array = App::call()->companiesContactsSpecialRepository->getAll();
        echo json_encode(['result' => $array]);
    }

}
