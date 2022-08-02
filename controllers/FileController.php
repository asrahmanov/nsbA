<?php

namespace app\controllers;

use app\engine\App;



class FileController extends Controller
{
    protected $defaultAction = 'file';


    public function actionFile()
    {
        echo "упс";
    }




    public function actionDelete()
    {
        $id = App::call()->request->getParams()['id'];
        $file = App::call()->filesRepository->getObject($id);
        $file->deleted = '1';
        $result = App::call()->filesRepository->save($file);
        echo json_encode(['result' => $result]);
    }


}
