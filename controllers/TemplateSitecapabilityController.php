<?php

namespace app\controllers;

use app\engine\App;
use app\models\entities\DiseasesSitecapability;
use app\models\entities\TemplateSitecapability;
use app\models\entities\Tissues;
use app\models\entities\TissuesSitecapability;


class TemplateSitecapabilityController extends Controller
{
    protected $layout = 'admin';
    protected $defaultAction = 'siteCapability';
    protected $render = 'siteCapability/templateSitecapability';

    public function actionSiteCapability()
    {
        echo $this->render($this->render);
    }

    public function actionEdit()
    {
        $this->render = 'siteCapability/edit';
        $id = App::call()->request->getParams()['id'];
        $template = App::call()->templateSitecapabilityRepository->getOne($id);

        echo $this->render($this->render, [
            'tempalte' => $template,
            'id' => $id
        ]);
    }

    public function actionReport()
    {
        $this->render = 'siteCapability/report';
        echo $this->render($this->render);
    }

    public function actionGetAll()
    {
        $pages = App::call()->templateSitecapabilityRepository->getAll();
        echo json_encode($pages);
    }

    public function actionSave()
    {

        if (isset(App::call()->request->getParams()['id'])) {
            $id = App::call()->request->getParams()['id'];
            $template = App::call()->templateSitecapabilityRepository->getObject($id);

        } else {
            $template = New TemplateSitecapability();
        }

        if (isset(App::call()->request->getParams()['name'])) {
            $name = App::call()->request->getParams()['name'];
            $template->name = $name;
        }

        $result = App::call()->templateSitecapabilityRepository->save($template);
        echo json_encode(['result' => $result]);

    }

    public function actionDell()
    {
        $id = App::call()->request->getParams()['id'];
        $template = App::call()->templateSitecapabilityRepository->getObject($id);
        $result = App::call()->templateSitecapabilityRepository->delete($template);
        echo json_encode(['result' => $result]);
    }

    public function actionSaveTissues()
    {
        $template_id = App::call()->request->getParams()['template_id'];
        $tissues = App::call()->request->getParams()['tissues'];
        $sorting = App::call()->request->getParams()['sorting'];
        $users_group = App::call()->request->getParams()['users_group'];

        $Tissues = new TissuesSitecapability();
        $Tissues->template_id = $template_id;
        $Tissues->tissues = $tissues;
        $Tissues->sorting = $sorting;
        $Tissues->users_group = $users_group;

        $result = App::call()->tissuesSitecapabilityRepository->save($Tissues);
        echo json_encode(['result' => $result]);


        $getTissues = App::call()->tissuesRepository->getWhere(['name' => $tissues]);
        if (!count($getTissues)) {
            $user_id = App::call()->session->getSession('user_id');
            $TissuesHistory = new Tissues();
            $TissuesHistory->name = $tissues;
            $TissuesHistory->user_id = $user_id;
            App::call()->tissuesRepository->save($TissuesHistory);
        }

    }

    public function actionSaveDiseases()
    {
        $template_id = App::call()->request->getParams()['template_id'];
        $name = App::call()->request->getParams()['disease'];
        $sorting = App::call()->request->getParams()['sorting'];
        $users_group = App::call()->request->getParams()['users_group'];

        $Disease = new DiseasesSitecapability();
        $Disease->template_id = $template_id;
        $Disease->disease = $name;
        $Disease->sorting = $sorting;
        $Disease->users_group = $users_group;

        $result = App::call()->diseasesSitecapabilityRepository->save($Disease);
        echo json_encode(['result' => $result]);
    }

    public function actionGetTableTissues()
    {

        $template_id = App::call()->request->getParams()['id'];
        $Tissues = App::call()->tissuesSitecapabilityRepository->getbyTepmlate($template_id);
        echo json_encode($Tissues);
    }

    public function actionGetTableTissuesAdmin()
    {

        $template_id = App::call()->request->getParams()['id'];
        $Tissues = App::call()->tissuesSitecapabilityRepository->getbyTepmlateAdmin($template_id);
        echo json_encode($Tissues);
    }

    public function actionGetTableDiseases()
    {

        $template_id = App::call()->request->getParams()['id'];
        $Diseases = App::call()->diseasesSitecapabilityRepository->getbyTepmlate($template_id);
        echo json_encode($Diseases);
    }

    public function actionGetTableDiseasesAdmin()
    {

        $template_id = App::call()->request->getParams()['id'];
        $Diseases = App::call()->diseasesSitecapabilityRepository->getbyTepmlateAdmin($template_id);
        echo json_encode($Diseases);
    }

    public function actionDeleteDisease()
    {
        $id = App::call()->request->getParams()['id'];

        $Disease = App::call()->diseasesSitecapabilityRepository->getObject($id);
        $result = App::call()->diseasesSitecapabilityRepository->delete($Disease);
        echo json_encode(['result' => $result]);
    }

    public function actionDeleteTissues()
    {
        $id = App::call()->request->getParams()['id'];
        $Tissues = App::call()->tissuesSitecapabilityRepository->getObject($id);
        $result = App::call()->tissuesSitecapabilityRepository->delete($Tissues);
        echo json_encode(['result' => $result]);
    }

    public function actionDiseaseAll()
    {


        if (isset(App::call()->request->getParams()['template_id'])) {
            $template_id = App::call()->request->getParams()['template_id'];
            $result = App::call()->diseasesSitecapabilityRepository->getAllDistinctByTemplate($template_id);
        } else {
            $result = App::call()->diseasesSitecapabilityRepository->getAllDistinct();
        }


        echo json_encode(['result' => $result]);
    }

    public function actionTissuesAll()
    {

        if (isset(App::call()->request->getParams()['template_id'])) {
            $template_id = App::call()->request->getParams()['template_id'];
            $result = App::call()->tissuesSitecapabilityRepository->getAllTissuesByTemplate($template_id);
        } else {
            $result = App::call()->tissuesSitecapabilityRepository->getAllTissues();
        }

        echo json_encode(['result' => $result]);
    }

    public function actionTemp()
    {

        $result = App::call()->templateSitecapabilityRepository->getAllSorn();
        echo json_encode(['result' => $result]);
    }

}
