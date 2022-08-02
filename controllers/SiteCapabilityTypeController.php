<?php

namespace app\controllers;

use app\engine\App;
use app\models\entities\SiteCapabilityType;
use app\models\entities\Tissues;


class SiteCapabilityTypeController extends Controller
{
    protected $layout = 'main';
    protected $defaultAction = '404';
    protected $render = 'tissues/tissues';

    public function action404()
    {
        echo $this->render('404');
    }

    public function actionGetAll()
    {
        $template_id = App::call()->request->getParams()['template_id'];
        $array = App::call()->siteCapabilityTypeRepository->getWhere([
            'template_id' => $template_id

        ]);
        echo json_encode($array);
    }


    public function actionSave(){



        if (isset(App::call()->request->getParams()['id'])) {
            $id= App::call()->request->getParams()['id'];
            $type= App::call()->siteCapabilityTypeRepository->getObject($id);
        } else {
            $type = New SiteCapabilityType();
        }


        if(isset(App::call()->request->getParams()['tissues_id'])) {
            $tissues_id = App::call()->request->getParams()['tissues_id'];
            $type->tissues_id = $tissues_id;
        }


        if(isset(App::call()->request->getParams()['diseases_id'])) {
            $diseases_id = App::call()->request->getParams()['diseases_id'];
            $type->diseases_id = $diseases_id;
        }


        if(isset(App::call()->request->getParams()['template_id'])) {
            $template_id = App::call()->request->getParams()['template_id'];
            $type->template_id = $template_id;
        }



        if(isset(App::call()->request->getParams()['typevalue'])) {
            $typevalue = App::call()->request->getParams()['typevalue'];
            $type->typevalue = $typevalue;
        }

        if(isset(App::call()->request->getParams()['placeholdervalue'])) {
            $placeholdervalue = App::call()->request->getParams()['placeholdervalue'];
            $type->placeholdervalue = $placeholdervalue;
        }


        $reslut =  App::call()->siteCapabilityTypeRepository->save($type);

        echo json_encode(['result' => $reslut]);



    }




}
