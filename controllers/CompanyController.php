<?php

namespace app\controllers;

use app\engine\App;
use app\models\entities\Company;


class CompanyController extends Controller
{
    protected $layout = 'admin';
    protected $defaultAction = 'company';


    public function actionCompany()
    {
        $role = App::call()->session->getSession('role_id');
        echo $this->render($this->defaultAction,[
            'role' => $role
        ]);
    }

    public function actionGetCompanyAll()
    {
        $fr_scripts = App::call()->companyRepository->getAll();

        $typeScript = [];
        $company_type = App::call()->typeScriptRepository->getAll();
        for ($i = 0; $i < count($company_type); $i++) {
            $typeScript[$company_type[$i]['id']] = $company_type[$i]['type_name'];
        }
        $currencyArray = [];
        $currency = App::call()->currencyRepository->getAll();
         for ($i = 0; $i < count($currency); $i++) {
             if(isset($currency[$i]['currency_id'])){
                 $currencyArray[$currency[$i]['currency_id']] = $currency[$i]['currency'];
             }
         }







        for ($i = 0; $i < count($fr_scripts); $i++) {
            $json['data'][$i]['script_id'] = $fr_scripts[$i]['script_id'];
            $json['data'][$i]['script'] = $fr_scripts[$i]['script'];
            $json['data'][$i]['company_name'] = $fr_scripts[$i]['company_name'];
            $json['data'][$i]['contacts'] = $fr_scripts[$i]['contacts'];
            $json['data'][$i]['last_script_num'] = $fr_scripts[$i]['last_script_num'];

            if(isset($currencyArray[$fr_scripts[$i]['currency']])) {
                $json['data'][$i]['currency'] = $currencyArray[$fr_scripts[$i]['currency']];
            } else {
                $json['data'][$i]['currency'] = 'USD';
            }

            $json['data'][$i]['currency_string'] = $fr_scripts[$i]['currency_string'];
            $json['data'][$i]['script_type'] = $typeScript[$fr_scripts[$i]['script_type']];
            $json['data'][$i]['priority'] = $fr_scripts[$i]['priority'];
            $json['data'][$i]['payment_terms'] = $fr_scripts[$i]['payment_terms'];
            $json['data'][$i]['contract_off'] = $fr_scripts[$i]['contract_off'];
            $json['data'][$i]['contract_comm'] = $fr_scripts[$i]['contract_comm'];
            $json['data'][$i]['status'] = $fr_scripts[$i]['status'];
            $json['data'][$i]['origin'] = $fr_scripts[$i]['origin'];



            if($fr_scripts[$i]['is_contract'] == 0) {
                $json['data'][$i]['is_contract'] = 'Нет';
            } else {
                $json['data'][$i]['is_contract'] = 'Да';
            }


        }
        echo json_encode($json);

    }


    public function actionGetCompanyPriority()
    {
        $fr_scripts = App::call()->companyRepository->getCompanyPriority();
        echo json_encode($fr_scripts);
    }


    public function actionInfo()
    {
        $role = App::call()->session->getSession('role_id');
        $companyId = App::call()->request->getParams()['companyId'];
        $company = App::call()->companyRepository->GetCompanyOne($companyId);
        $company_type = App::call()->typeScriptRepository->getAll();
        $marketplace = App::call()->marketplaceRepository->getAll();
        $currency = App::call()->currencyRepository->getAll();

        echo $this->render('companyInfo',
            [
                'company' => $company,
                'company_type' => $company_type,
                'marketplace' => $marketplace,
                'currency' => $currency,
                'role' => $role
            ]
        );
    }


    public function actionSave()
    {

        if (isset(App::call()->request->getParams()['script_id'])) {
            $script_id = App::call()->request->getParams()['script_id'];
            $company = App::call()->companyRepository->getObject($script_id);

        } else {
            $company = New Company();
        }


        if (isset(App::call()->request->getParams()['script'])) {
            $script = App::call()->request->getParams()['script'];
            $company->script = $script;
        }

        if (isset(App::call()->request->getParams()['company_name'])) {
            $company_name = App::call()->request->getParams()['company_name'];
            $company->company_name = $company_name;
        }

        if (isset(App::call()->request->getParams()['contacts'])) {
            $contacts = App::call()->request->getParams()['contacts'];
            $company->contacts = $contacts;
        }

        if (isset(App::call()->request->getParams()['last_script_num'])) {
            $last_script_num = App::call()->request->getParams()['last_script_num'];
            $company->last_script_num = $last_script_num;
        }

        if (isset(App::call()->request->getParams()['currency'])) {
            $currency = App::call()->request->getParams()['currency'];
            $company->currency = $currency;
        }

        if (isset(App::call()->request->getParams()['script_type'])) {
            $script_type = App::call()->request->getParams()['script_type'];
            $company->script_type = $script_type;
        }

        if (isset(App::call()->request->getParams()['script_type'])) {
            $priority = App::call()->request->getParams()['priority'];
            $company->priority = $priority;
        }

        if (isset(App::call()->request->getParams()['payment_terms'])) {
            $payment_terms = App::call()->request->getParams()['payment_terms'];
            $company->payment_terms = $payment_terms;
        }

        if (isset(App::call()->request->getParams()['legal_address'])) {
            $legal_address = App::call()->request->getParams()['legal_address'];
            $company->legal_address = $legal_address;
        }

        if (isset(App::call()->request->getParams()['origin'])) {
            $origin = App::call()->request->getParams()['origin'];
            $company->origin = $origin;
        }

        if (isset(App::call()->request->getParams()['status'])) {
            $status = App::call()->request->getParams()['status'];
            $company->status = $status;
        }

        if (isset(App::call()->request->getParams()['marketplace_id'])) {
            $marketplace_id = App::call()->request->getParams()['marketplace_id'];
            $company->marketplace_id = $marketplace_id;
        }

        if (isset(App::call()->request->getParams()['contract_comm'])) {
            $contract_comm = App::call()->request->getParams()['contract_comm'];
            $company->contract_comm = $contract_comm;
        }

        if (isset(App::call()->request->getParams()['contract_off'])) {
            $contract_off = App::call()->request->getParams()['contract_off'];
            $company->contract_off = $contract_off;
        }

        if (isset(App::call()->request->getParams()['is_contract'])) {
            $is_contract = App::call()->request->getParams()['is_contract'];
            $company->is_contract = $is_contract;
        }

        if (isset(App::call()->request->getParams()['deleted'])) {
            $deleted = App::call()->request->getParams()['deleted'];
            $company->deleted = $deleted;
        }

        $result = App::call()->companyRepository->save($company);
        echo $result;
    }


    public function actionAdd()
    {
        $company_type = App::call()->typeScriptRepository->getAll();
        $marketplace = App::call()->marketplaceRepository->getAll();
        $currency = App::call()->currencyRepository->getAll();
        echo $this->render('compamyAdd', [
            'company_type' => $company_type,
            'marketplace' => $marketplace,
            'currency' => $currency
        ]);
    }


    public function actionGetOne(){
        $id = App::call()->request->getParams()['id'];
        $result = App::call()->companyRepository->getOne($id);
        echo json_encode(['result' => $result]);
    }

    public function actionDeleted(){
        $script_id = App::call()->request->getParams()['script_id'];
        $company = App::call()->companyRepository->getObject($script_id);
        $company->deleted = 1;
        $result = App::call()->companyRepository->save($company);
        echo json_encode(['result' => $result]);
    }


}
