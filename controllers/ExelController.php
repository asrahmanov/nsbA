<?php

namespace app\controllers;

use app\engine\App;



class ExelController extends Controller
{
    protected $defaultAction = 'exel';


    public function actionElex()
    {
        echo "упс";
    }




    public function actionExport()
    {
        header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header("Content-Disposition: attachment;filename=\"frExport.xls\"");
        header("Cache-Control: max-age=0");
//        $text = preg_replace('|<input type="checkbox" class="mclinic_checkbox check_ejesut_1c" checked.*>|Ui', '1', urldecode($_POST['table']));
//        $text = preg_replace('|<input type="checkbox" class="mclinic_checkbox check_ejesut_home" checked.*>|Ui', '1', $text);
//        $text = strip_tags($text, '<table><thead><tbody><th><td><a>');
        echo urldecode($_POST['table']);
    }


}
