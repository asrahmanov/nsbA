<?php

namespace app\controllers;

use app\engine\App;
use app\models\entities\DiseasesBiospecimenTypes;
use app\models\entities\Files;
use app\models\entities\LogChange;
use app\models\entities\Mail;
use app\models\entities\NewTickets;
use app\models\entities\Offer;
use app\models\entities\OfferRu;
use app\models\entities\OfferApe;
use app\models\entities\OfferApproval;
use app\models\entities\OrderDiseases;
use app\models\entities\Orders;
use app\models\entities\OrdersStatusActions;
use app\models\entities\Po;
use app\models\entities\PoDisease;
use app\models\entities\PoDiseaseSampleMod;
use app\models\entities\Priority;
use app\models\entities\Quote;
use app\models\entities\QuoteDoctor;
use app\models\entities\QuoteSample;
use app\models\entities\Vacation;
use app\models\entities\WorksheetsInvertory;
use app\models\entities\WorksheetsLaboratory;


class OrdersController extends Controller
{
    protected $layout = 'main';
    protected $defaultAction = 'orders';
    protected $render = 'orders/orders';


    public function actionOrders()
    {


        $role_id = App::call()->session->getSession('role_id');
        if ($role_id == 3 or $role_id == 8) {
            $this->render = 'orders/manager';
        }

        $role = App::call()->session->getSession('role_id');

        $script = [];
        $fr_scripts = App::call()->companyRepository->getAllSort();
        for ($i = 0; $i < count($fr_scripts); $i++) {
            $script[] = $fr_scripts[$i];
        }


        $status = [];
        $fr_status = App::call()->frstatusRepository->getAll();
        for ($i = 0; $i < count($fr_status); $i++) {
            $status[] = $fr_status[$i];
        }

        $users = [];
        $nbs_users = App::call()->usersRepository->getWhere(['role_id' => 1]);
        for ($i = 0; $i < count($nbs_users); $i++) {
            $users[] = $nbs_users[$i];
        }


        $orderStatus = [];
        $nbs_orderStatus = App::call()->ordersStatusRepository->getAll();
        for ($i = 0; $i < count($nbs_orderStatus); $i++) {
            $orderStatus[] = $nbs_orderStatus[$i];
        }

        $dateOne = DATE('Y-m-d');
        $dateAt = strtotime('-3 MONTH', strtotime($dateOne));
        $dateTwo = date('Y-m-d', $dateAt);

        $myId = App::call()->session->getSession('user_id');
        $me = App::call()->usersRepository->getOne($myId);
        $department_id = $me['department_id'];
        echo $this->render($this->render, [
            'script' => $script,
            'status' => $status,
            'role' => $role,
            'users' => $users,
            'orstatus' => $orderStatus,
            'dateOne' => $dateOne,
            'dateTwo' => $dateTwo,
            'department_id' => $department_id,
            'user_id' => $myId
        ]);

    }

    public function actionMy()
    {
        $role = App::call()->session->getSession('role_id');

        $script = [];
        $fr_scripts = App::call()->companyRepository->getAllSort();
        for ($i = 0; $i < count($fr_scripts); $i++) {
            $script[] = $fr_scripts[$i];
        }


        $status = [];
        $fr_status = App::call()->frstatusRepository->getAll();
        for ($i = 0; $i < count($fr_status); $i++) {
            $status[] = $fr_status[$i];
        }

        $users = [];
        $nbs_users = App::call()->usersRepository->getWhere(['role_id' => 1]);
        for ($i = 0; $i < count($nbs_users); $i++) {
            $users[] = $nbs_users[$i];
        }


        echo $this->render('home', [
            'script' => $script,
            'status' => $status,
            'role' => $role,
            'users' => $users
        ]);

    }

    public function actionAdd()
    {
        $company = [];

        $fr_scripts = App::call()->companyRepository->getAll();
        for ($i = 0; $i < count($fr_scripts); $i++) {
            $company[] = $fr_scripts[$i];
        }

        $status = [];
        $fr_status = App::call()->frstatusRepository->getAll();
        for ($i = 0; $i < count($fr_status); $i++) {
            $status[] = $fr_status[$i];
        }

        $users = [];
        $nbs_users = App::call()->usersRepository->getWhere(['role_id' => 1]);
        for ($i = 0; $i < count($nbs_users); $i++) {
            $users[] = $nbs_users[$i];
        }

        $orderStatus = [];
        $nbs_orderStatus = App::call()->ordersStatusRepository->getAll();
        for ($i = 0; $i < count($nbs_orderStatus); $i++) {
            $orderStatus[] = $nbs_orderStatus[$i];
        }

        $user_id = App::call()->session->getSession('user_id');
        echo $this->render('orders/ordersAdd', [
            'company' => $company,
            'status' => $status,
            'users' => $users,
            'orstatus' => $orderStatus,
            'user_id' => $user_id
        ]);

    }

    public function actionGetOrdersAll()
    {

        $users = [];

        $nbs_users = App::call()->usersRepository->getAll();

        for ($i = 0; $i < count($nbs_users); $i++) {
            $users[$nbs_users[$i]['id']] = $nbs_users[$i]['lasttname'] . ' ' . $nbs_users[$i]['firstname'] . ' ' . $nbs_users[$i]['patronymic'];
        }

        $status = [];
        $fr_status = App::call()->frstatusRepository->getAll();
        for ($i = 0; $i < count($fr_status); $i++) {
            $status[$fr_status[$i]['fr_status_id']] = $fr_status[$i]['fr_status_values'];
        }


        $priority = App::call()->priorityRepository->getAll();
        $priorityArray = [];
        for ($i = 0; $i < count($priority); $i++) {
            $priorityArray[$priority[$i]['id']] = $priority[$i]['name'];
        }

        $quote = App::call()->quoteRepository->getMaxValue();
        $quotes = [];
        for ($i = 0; $i < count($quote); $i++) {
            $quotes[$quote[$i]['proj_id']] = $quote[$i]['total'];
        }
        $dateOne = App::call()->request->getParams()['dateOne'];
        $dateTwo = App::call()->request->getParams()['dateTwo'];
        // Для наблюдателя роль 2 вывод со своей сортировкой
        $role = App::call()->session->getSession('role_id');
        if ($role == 2) {
            if ($dateOne != '' and $dateTwo != '') {
                $fr_table = App::call()->ordersRepository->getAllbyDateDesc($dateTwo, $dateOne);
            } else {
                $fr_table = App::call()->ordersRepository->getOrderByDesc($dateTwo, $dateOne);
            }
        } else {
            if ($dateOne != '' and $dateTwo != '') {
                $fr_table = App::call()->ordersRepository->getAllbyDateMin($dateTwo, $dateOne);
            } else {
                $fr_table = App::call()->ordersRepository->getAll();
            }
        }

        $orderStatus = [];
        $nbs_orderStatus = App::call()->ordersStatusRepository->getAll();
        for ($i = 0; $i < count($nbs_orderStatus); $i++) {
            $orderStatus[$nbs_orderStatus[$i]['id']] = $nbs_orderStatus[$i]['status_name'];
        }

        $staff = [];

        $nbs_staff = App::call()->companyStaffRepository->getAll();
        for ($i = 0; $i < count($nbs_staff); $i++) {
            $staff[$nbs_staff[$i]['id']] = $nbs_staff[$i]['name'];
        }

        for ($i = 0; $i < count($fr_table); $i++) {
            $json['data'][$i]['proj_id'] = $fr_table[$i]['proj_id'];
            $json['data'][$i]['fr_date'] = $fr_table[$i]['fr_date'];
            $json['data'][$i]['linked_fr_id'] = $fr_table[$i]['linked_fr_id'];
            $json['data'][$i]['internal_id'] = $fr_table[$i]['internal_id'];
            $json['data'][$i]['external_id'] = $fr_table[$i]['external_id'];
            $json['data'][$i]['project_name'] = $fr_table[$i]['project_name'];
            $json['data'][$i]['project_details'] = $fr_table[$i]['project_details'];
            $json['data'][$i]['comments'] = $fr_table[$i]['comments'];
            $json['data'][$i]['feas_russian'] = $fr_table[$i]['feas_russian'];

            if (isset($priorityArray[$fr_table[$i]['priority_id']])) {
                $json['data'][$i]['priority'] = $priorityArray[$fr_table[$i]['priority_id']];
            } else {
                $json['data'][$i]['priority'] = 'Приоритет не установлен';
            }

            if (isset($status[$fr_table[$i]['fr_status']])) {
                $json['data'][$i]['fr_status'] = $status[$fr_table[$i]['fr_status']];
            } else {
                $json['data'][$i]['fr_status'] = $fr_table[$i]['fr_status'];
            }


            if (isset($staff[$fr_table[$i]['attention_to']])) {
                $json['data'][$i]['attention_to'] = $staff[$fr_table[$i]['attention_to']];
            } else {
                $json['data'][$i]['attention_to'] = 'Не закреплен';
            }

            $json['data'][$i]['fr_status_date'] = $fr_table[$i]['fr_status_date'];
            $json['data'][$i]['currency'] = $fr_table[$i]['currency'];
            $json['data'][$i]['clinical_inclusion'] = $fr_table[$i]['clinical_inclusion'];
            $json['data'][$i]['quotes_from_managers'] = $fr_table[$i]['quotes_from_managers'];
            $json['data'][$i]['unformal_quote'] = $fr_table[$i]['unformal_quote'];
            $json['data'][$i]['quote_date'] = $fr_table[$i]['quote_date'];
            $json['data'][$i]['script_number'] = $fr_table[$i]['script_number'];

            if (isset($users[$fr_table[$i]['answering_id']])) {
                $json['data'][$i]['answering_id'] = $users[$fr_table[$i]['answering_id']];
                $json['data'][$i]['users_id'] = $fr_table[$i]['answering_id'];
            } else {
                $json['data'][$i]['answering_id'] = 'Не закреплен';
                $json['data'][$i]['users_id'] = 0;
            }

            $json['data'][$i]['deadline'] = $fr_table[$i]['deadline'];
            $json['data'][$i]['deadline_post'] = $fr_table[$i]['deadline_post'];
            $json['data'][$i]['deadline_quote'] = $fr_table[$i]['deadline_quote'];


            if (isset($quotes[$fr_table[$i]['proj_id']])) {
                $json['data'][$i]['value_mount'] = $quotes[$fr_table[$i]['proj_id']];
            } else {
                $json['data'][$i]['value_mount'] = 0;
            }

            $json['data'][$i]['status_manager'] = $orderStatus[$fr_table[$i]['status_manager']];
            $json['data'][$i]['status_project'] = $orderStatus[$fr_table[$i]['status_project']];
            $json['data'][$i]['status_lpo'] = $orderStatus[$fr_table[$i]['status_lpo']];
            $json['data'][$i]['status_ved'] = $orderStatus[$fr_table[$i]['status_ved']];
            $json['data'][$i]['status_boss'] = $orderStatus[$fr_table[$i]['status_boss']];
            $json['data'][$i]['status_client'] = $orderStatus[$fr_table[$i]['status_client']];


        }
        echo json_encode($json);

    }

    public function actionGetOrdersStatus()
    {

        $users = [];
        $nbs_users = App::call()->usersRepository->getAll();
        for ($i = 0; $i < count($nbs_users); $i++) {
            $users[$nbs_users[$i]['id']] = $nbs_users[$i]['lasttname'] . ' ' . $nbs_users[$i]['firstname'] . ' ' . $nbs_users[$i]['patronymic'];
        }

        $status = [];
        $fr_status = App::call()->frstatusRepository->getAll();
        for ($i = 0; $i < count($fr_status); $i++) {
            $status[$fr_status[$i]['fr_status_id']] = $fr_status[$i]['fr_status_values'];
        }


        $priority = App::call()->priorityRepository->getAll();
        $priorityArray = [];
        for ($i = 0; $i < count($priority); $i++) {
            $priorityArray[$priority[$i]['id']] = $priority[$i]['name'];
        }

        $quote = App::call()->quoteRepository->getMaxValue();
        $quotes = [];
        for ($i = 0; $i < count($quote); $i++) {
            $quotes[$quote[$i]['proj_id']] = $quote[$i]['total'];
        }


        $orderStatus = [];
        $nbs_orderStatus = App::call()->ordersStatusRepository->getAll();
        for ($i = 0; $i < count($nbs_orderStatus); $i++) {
            $orderStatus[$nbs_orderStatus[$i]['id']] = $nbs_orderStatus[$i]['status_name'];
        }

        $staff = [];

        $nbs_staff = App::call()->companyStaffRepository->getAll();
        for ($i = 0; $i < count($nbs_staff); $i++) {
            $staff[$nbs_staff[$i]['id']] = $nbs_staff[$i]['name'];
        }

        $fr_table = App::call()->ordersRepository->getManagerAll();

        for ($i = 0; $i < count($fr_table); $i++) {
            $json['data'][$i]['proj_id'] = $fr_table[$i]['proj_id'];
            $json['data'][$i]['fr_date'] = $fr_table[$i]['fr_date'];
            $json['data'][$i]['linked_fr_id'] = $fr_table[$i]['linked_fr_id'];
            $json['data'][$i]['internal_id'] = $fr_table[$i]['internal_id'];
            $json['data'][$i]['external_id'] = $fr_table[$i]['external_id'];
            $json['data'][$i]['project_name'] = $fr_table[$i]['project_name'];
            $json['data'][$i]['project_details'] = $fr_table[$i]['project_details'];
            $json['data'][$i]['comments'] = $fr_table[$i]['comments'];
            $json['data'][$i]['feas_russian'] = $fr_table[$i]['feas_russian'];

            if (isset($priorityArray[$fr_table[$i]['priority_id']])) {
                $json['data'][$i]['priority'] = $priorityArray[$fr_table[$i]['priority_id']];
            } else {
                $json['data'][$i]['priority'] = 'Приоритет не установлен';
            }

            if (isset($status[$fr_table[$i]['fr_status']])) {
                $json['data'][$i]['fr_status'] = $status[$fr_table[$i]['fr_status']];
            } else {
                $json['data'][$i]['fr_status'] = $fr_table[$i]['fr_status'];
            }


            $json['data'][$i]['fr_status_date'] = $fr_table[$i]['fr_status_date'];
            $json['data'][$i]['currency'] = $fr_table[$i]['currency'];
            $json['data'][$i]['clinical_inclusion'] = $fr_table[$i]['clinical_inclusion'];
            $json['data'][$i]['quotes_from_managers'] = $fr_table[$i]['quotes_from_managers'];
            $json['data'][$i]['unformal_quote'] = $fr_table[$i]['unformal_quote'];
            $json['data'][$i]['quote_date'] = $fr_table[$i]['quote_date'];
            $json['data'][$i]['script_number'] = $fr_table[$i]['script_number'];

            if (isset($users[$fr_table[$i]['answering_id']])) {
                $json['data'][$i]['answering_id'] = $users[$fr_table[$i]['answering_id']];
                $json['data'][$i]['users_id'] = $fr_table[$i]['answering_id'];
            } else {
                $json['data'][$i]['answering_id'] = 'Не закреплен';
                $json['data'][$i]['users_id'] = 0;
            }

            $json['data'][$i]['deadline'] = $fr_table[$i]['deadline'];
            $json['data'][$i]['deadline_post'] = $fr_table[$i]['deadline_post'];
            $json['data'][$i]['deadline_quote'] = $fr_table[$i]['deadline_quote'];

            if (isset($quotes[$fr_table[$i]['proj_id']])) {
                $json['data'][$i]['value_mount'] = $quotes[$fr_table[$i]['proj_id']];
            } else {
                $json['data'][$i]['value_mount'] = 0;
            }

            if (isset($staff[$fr_table[$i]['attention_to']])) {
                $json['data'][$i]['attention_to'] = $staff[$fr_table[$i]['attention_to']];
            } else {
                $json['data'][$i]['attention_to'] = 'Не закреплен';
            }


            $json['data'][$i]['status_client'] = $orderStatus[$fr_table[$i]['status_client']];
            $json['data'][$i]['status_manager'] = $orderStatus[$fr_table[$i]['status_manager']];
            $json['data'][$i]['status_project'] = $orderStatus[$fr_table[$i]['status_project']];
            $json['data'][$i]['status_lpo'] = $orderStatus[$fr_table[$i]['status_lpo']];
            $json['data'][$i]['status_ved'] = $orderStatus[$fr_table[$i]['status_ved']];
            $json['data'][$i]['status_boss'] = $orderStatus[$fr_table[$i]['status_boss']];

        }
        echo json_encode($json);

    }

    public function actionGetOrdersMy()
    {
        $users = [];
        $nbs_users = App::call()->usersRepository->getAll();
        for ($i = 0; $i < count($nbs_users); $i++) {
            $users[$nbs_users[$i]['id']] = $nbs_users[$i]['lasttname'] . ' ' . $nbs_users[$i]['firstname'] . ' ' . $nbs_users[$i]['patronymic'];
        }

        $status = [];
        $fr_status = App::call()->frstatusRepository->getAll();
        for ($i = 0; $i < count($fr_status); $i++) {
            $status[$fr_status[$i]['fr_status_id']] = $fr_status[$i]['fr_status_values'];
        }

        $priority = App::call()->priorityRepository->getAll();
        $priorityArray = [];
        for ($i = 0; $i < count($priority); $i++) {
            $priorityArray[$priority[$i]['id']] = $priority[$i]['name'];
        }

        $quote = App::call()->quoteRepository->getMaxValue();
        $quotes = [];
        for ($i = 0; $i < count($quote); $i++) {
            $quotes[$quote[$i]['proj_id']] = $quote[$i]['total'];
        }

        $orderStatus = [];
        $nbs_orderStatus = App::call()->ordersStatusRepository->getAll();
        for ($i = 0; $i < count($nbs_orderStatus); $i++) {
            $orderStatus[$nbs_orderStatus[$i]['id']] = $nbs_orderStatus[$i]['status_name'];
        }

        $staff = [];

        $nbs_staff = App::call()->companyStaffRepository->getAll();
        for ($i = 0; $i < count($nbs_staff); $i++) {
            $staff[$nbs_staff[$i]['id']] = $nbs_staff[$i]['name'];
        }

        $fr_table = App::call()->ordersRepository->GetOrdersMy();
        for ($i = 0; $i < count($fr_table); $i++) {
            $json['data'][$i]['proj_id'] = $fr_table[$i]['proj_id'];
            $json['data'][$i]['fr_date'] = $fr_table[$i]['fr_date'];
            $json['data'][$i]['linked_fr_id'] = $fr_table[$i]['linked_fr_id'];
            $json['data'][$i]['internal_id'] = $fr_table[$i]['internal_id'];
            $json['data'][$i]['external_id'] = $fr_table[$i]['external_id'];
            $json['data'][$i]['project_name'] = $fr_table[$i]['project_name'];
            $json['data'][$i]['project_details'] = $fr_table[$i]['project_details'];
            $json['data'][$i]['comments'] = $fr_table[$i]['comments'];
            $json['data'][$i]['feas_russian'] = $fr_table[$i]['feas_russian'];

            if (isset($priorityArray[$fr_table[$i]['priority_id']])) {
                $json['data'][$i]['priority'] = $priorityArray[$fr_table[$i]['priority_id']];
            } else {
                $json['data'][$i]['priority'] = 'Приоритет не установлен';
            }

            if (isset($status[$fr_table[$i]['fr_status']])) {
                $json['data'][$i]['fr_status'] = $status[$fr_table[$i]['fr_status']];
            } else {
                $json['data'][$i]['fr_status'] = $fr_table[$i]['fr_status'];
            }


            $json['data'][$i]['fr_status_date'] = $fr_table[$i]['fr_status_date'];
            $json['data'][$i]['currency'] = $fr_table[$i]['currency'];
            $json['data'][$i]['clinical_inclusion'] = $fr_table[$i]['clinical_inclusion'];
            $json['data'][$i]['quotes_from_managers'] = $fr_table[$i]['quotes_from_managers'];
            $json['data'][$i]['unformal_quote'] = $fr_table[$i]['unformal_quote'];
            $json['data'][$i]['quote_date'] = $fr_table[$i]['quote_date'];
            $json['data'][$i]['script_number'] = $fr_table[$i]['script_number'];

            if (isset($users[$fr_table[$i]['answering_id']])) {
                $json['data'][$i]['answering_id'] = $users[$fr_table[$i]['answering_id']];
                $json['data'][$i]['users_id'] = $fr_table[$i]['answering_id'];
            } else {
                $json['data'][$i]['answering_id'] = 'Не закреплен';
                $json['data'][$i]['users_id'] = 0;
            }


            if (isset($staff[$fr_table[$i]['attention_to']])) {
                $json['data'][$i]['attention_to'] = $staff[$fr_table[$i]['attention_to']];
            } else {
                $json['data'][$i]['attention_to'] = 'Не закреплен';
            }


            $json['data'][$i]['deadline'] = $fr_table[$i]['deadline'];
            $json['data'][$i]['deadline_post'] = $fr_table[$i]['deadline_post'];
            $json['data'][$i]['deadline_quote'] = $fr_table[$i]['deadline_quote'];


            if (isset($quotes[$fr_table[$i]['proj_id']])) {
                $json['data'][$i]['value_mount'] = $quotes[$fr_table[$i]['proj_id']];
            } else {
                $json['data'][$i]['value_mount'] = 0;
            }

            $json['data'][$i]['status_client'] = $orderStatus[$fr_table[$i]['status_client']];
            $json['data'][$i]['status_manager'] = $orderStatus[$fr_table[$i]['status_manager']];
            $json['data'][$i]['status_project'] = $orderStatus[$fr_table[$i]['status_project']];
            $json['data'][$i]['status_lpo'] = $orderStatus[$fr_table[$i]['status_lpo']];
            $json['data'][$i]['status_ved'] = $orderStatus[$fr_table[$i]['status_ved']];
            $json['data'][$i]['status_boss'] = $orderStatus[$fr_table[$i]['status_boss']];

        }


        if (!count($json['data'])) {

            $json['data'][0]['proj_id'] = null;
            $json['data'][0]['fr_date'] = null;
            $json['data'][0]['linked_fr_id'] = null;
            $json['data'][0]['internal_id'] = null;
            $json['data'][0]['external_id'] = null;
            $json['data'][0]['project_name'] = null;
            $json['data'][0]['project_details'] = null;
            $json['data'][0]['comments'] = null;
            $json['data'][0]['feas_russian'] = null;
            $json['data'][0]['fr_status'] = null;
            $json['data'][0]['fr_status_date'] = null;
            $json['data'][0]['currency'] = null;
            $json['data'][0]['clinical_inclusion'] = null;
            $json['data'][0]['quotes_from_managers'] = null;
            $json['data'][0]['unformal_quote'] = null;
            $json['data'][0]['quote_date'] = null;
            $json['data'][0]['answering_id'] = null;
            $json['data'][0]['priority'] = null;
            $json['data'][0]['deadline'] = null;
            $json['data'][0]['deadline_post'] = null;
            $json['data'][0]['deadline_quote'] = null;
            $json['data'][0]['value_mount'] = null;
            $json['data'][0]['status_manager'] = null;
            $json['data'][0]['status_project'] = null;
            $json['data'][0]['status_lpo'] = null;
            $json['data'][0]['status_ved'] = null;
            $json['data'][0]['status_boss'] = null;
            $json['data'][0]['status_client'] = null;

        }
        echo json_encode($json);

    }

    public function actionInfo()
    {
        $user_id = App::call()->session->getSession('user_id');

        $idFR = App::call()->request->getParams()['idFR'];


        $fr = App::call()->ordersRepository->GetOrdersOne($idFR);

        $fr_scripts = App::call()->companyRepository->getAll();

        $status = [];
        $fr_status = App::call()->frstatusRepository->getAll();
        for ($i = 0; $i < count($fr_status); $i++) {
            $status[] = $fr_status[$i];
        }


        $users = [];
        $nbs_users = App::call()->usersRepository->getWhere(['role_id' => 1]);
        for ($i = 0; $i < count($nbs_users); $i++) {
            $users[] = $nbs_users[$i];
        }

        $files = [];
        $nbs_files = App::call()->filesRepository->getFilesbyProjId($idFR);
        for ($i = 0; $i < count($nbs_files); $i++) {
            $files[] = $nbs_files[$i];
        }


        $diseases = App::call()->diseaseRepository->getAll();
        $diseasesById = new \stdClass();
        foreach ($diseases as $disease) {
            $diseasesById->{$disease['id']} = $disease;
        }
        $biospecimenTypes = App::call()->biospecimenTypeRepository->getAll();
        $biospecimenTypesById = new \stdClass();
        foreach ($biospecimenTypes as $biospecimenType) {
            $biospecimenTypesById->{$biospecimenType['id']} = $biospecimenType;
        }
        $sampleMods = App::call()->sampleModRepository->getAll();
        $sampleModsById = new \stdClass();
        foreach ($sampleMods as $sampleMod) {
            $sampleModsById->{$sampleMod['id']} = $sampleMod;
        }
        $orderDiseases = App::call()->orderDiseasesRepository->getAll();
        $orderDiseasesById = new \stdClass();
        foreach ($orderDiseases as $orderDisease) {
            $orderDiseasesById->{$orderDisease['id']} = $orderDisease;
        }
        $samples = App::call()->diseasesBiospecimenTypesRepository->getAll();
        $samplesById = new \stdClass();
        foreach ($samples as $sample) {
            $samplesById->{$sample['id']} = $sample;
        }
        $quoteDoctors = App::call()->quoteDoctorRepository->getAll();
        $quoteDoctorsById = new \stdClass();
        foreach ($quoteDoctors as $quoteDoctor) {
            $quoteDoctorsById->{$quoteDoctor['id']} = $quoteDoctor;
        }
        $doctors = App::call()->companiesContactsRepository->getAll();
        $doctorsById = new \stdClass();
        foreach ($doctors as $doctor) {
            $doctorsById->{$doctor['id']} = $doctor;
        }


        $quotes = App::call()->quoteRepository->getQuotebyProj($idFR);
        $quoteIds = [];
        foreach ($quotes as $key => $quote) {
            $quoteIds[] = $quote['id'];
            $nbs_user = App::call()->usersRepository->getWhere(['id' => $quote['user_id']]);
            $quotes[$key]['department_id'] = $nbs_user[0]['department_id'];

            $disease = $orderDiseasesById->{$quote['disease_id']};
            $disease_name = $diseasesById->{$disease['disease_id']};
            $quotes[$key]['disease'] = "{$disease_name['disease_name']} ({$disease_name['disease_name_russian_old']})";
            $quotes[$key]['disease_id_db'] = $disease['disease_id'];
            $disease_biospecimen_types = App::call()->quoteSampleRepository->getWhere(['quote_id' => $quotes[$key]['id']]);
            foreach ($disease_biospecimen_types as $index => $disease_biospecimen_type) {
                $biospecimen_type = App::call()->biospecimenTypeRepository->getOne($disease_biospecimen_type['biospecimen_type_id']);
                $disease_biospecimen_types[$index]['biospecimen_type'] = $biospecimen_type['biospecimen_type'];
                $disease_biospecimen_types[$index]['biospecimen_type_russian'] = $biospecimen_type['biospecimen_type_russian'];
                $mod = App::call()->sampleModRepository->getOne($disease_biospecimen_type['mod_id']);
                $disease_biospecimen_types[$index]['mod'] = $mod['modification'];
                $disease_biospecimen_types[$index]['enabled'] = true;
                $disease_biospecimen_types[$index]['db_id'] = $disease_biospecimen_type['id'];
            }
            $quotes[$key]['samples_table'] = $disease_biospecimen_types;
            $doctor_payments = [];
            foreach ($quoteDoctorsById as $QDid => $quoteDoctor) {
                if ($quoteDoctor['quote_id'] === $quote['id']) {
                    $doctor = $doctorsById->{$quoteDoctor['doc_id']};
                    $quoteDoctorsById->{$QDid}['doc_name'] = "{$doctor['lastname']} {$doctor['firstname']} {$doctor['patronymic']}";
                    $doctor_payments[] = $quoteDoctorsById->{$QDid};
                }
            }
            $quotes[$key]['doctor_payments'] = $doctor_payments;
            $quote_creation_datetime_frags = explode(' ', $quote['created_at']);
            $creation_date_frags = explode('-', $quote_creation_datetime_frags[0]);
            $quotes[$key]['created_at'] = "{$creation_date_frags[2]}.{$creation_date_frags[1]}.{$creation_date_frags[0]}";
        }

        $order_diseases = App::call()->orderDiseasesRepository->getWhere(['order_id' => $idFR]);
        foreach ($order_diseases as $key => $order_disease) {
            $disease = $diseasesById->{$order_disease['disease_id']};
            $order_diseases[$key]['disease'] = $disease['disease_name'];
            $order_diseases[$key]['mutation'] = $disease['disease_name_russian_old'];
            $order_diseases[$key]['disease_name_russian'] = $disease['disease_name_russian'];
        }
        $diseases_biospecimen_types = App::call()->diseasesBiospecimenTypesRepository->getWhere(['order_id' => $idFR]);
        foreach ($diseases_biospecimen_types as $key => $diseases_biospecimen_type) {
            $biospecimen_type = $biospecimenTypesById->{$diseases_biospecimen_type['biospecimen_type_id']};
            $diseases_biospecimen_types[$key]['biospecimen_type'] = $biospecimen_type['biospecimen_type'];
            $modification = $sampleModsById->{$diseases_biospecimen_type['mod_id']};
            $diseases_biospecimen_types[$key]['modification'] = $modification['modification'];
        }

        $diseases_biospecimen_types_rus = App::call()->diseasesBiospecimenTypesRepository->getWhere(['order_id' => $idFR]);
        foreach ($diseases_biospecimen_types as $key => $diseases_biospecimen_type) {
            $biospecimen_type = $biospecimenTypesById->{$diseases_biospecimen_type['biospecimen_type_id']};
            $diseases_biospecimen_types[$key]['biospecimen_type'] = $biospecimen_type['biospecimen_type'];
            $diseases_biospecimen_types_rus[$key]['biospecimen_type'] = $biospecimen_type['biospecimen_type_russian'];
            $modification = $sampleModsById->{$diseases_biospecimen_type['mod_id']};
            $diseases_biospecimen_types[$key]['modification'] = $modification['modification'];
            $diseases_biospecimen_types_rus[$key]['modification'] = $modification['modification'];
        }

        $history_quotes = [];
        $historyQuoteIds = [];


        foreach ($order_diseases as $order_disease) {
            $disease_biospecimen_types = App::call()->diseasesBiospecimenTypesRepository->getWhere(['order_id' => $idFR, 'disease_id' => $order_disease['disease_id']]);
            foreach ($disease_biospecimen_types as $disease_biospecimen_type) {
                $quotesByDiseaseAndSamples = App::call()->quoteRepository->getHistoryQuotesByDiseaseAndSampleIds($order_disease['disease_id'], $disease_biospecimen_type['biospecimen_type_id']);
                foreach ($quotesByDiseaseAndSamples as $quote) {
                    if (!array_search($quote['id'], $historyQuoteIds)) {
                        $historyQuoteIds[] = $quote['id'];
                        $nbs_user = App::call()->usersRepository->getWhere(['id' => $quote['user_id']]);
                        $quote['department_id'] = $nbs_user[0]['department_id'];
                        $history_quotes[] = $quote;


                    }
                }
            }
        }
        foreach ($history_quotes as $key => $quote) {
//            $disease = $orderDiseasesById->{$quote['disease_id']};
            $disease_name = $diseasesById->{$quote['disease_id']};
            $history_quotes[$key]['disease'] = "{$disease_name['disease_name']} ({$disease_name['disease_name_russian_old']})";
            $history_quotes[$key]['disease_id_db'] = isset($disease['disease_id']) ? $disease['disease_id'] : 0;
            $disease_biospecimen_types = App::call()->quoteSampleRepository->getWhere(['quote_id' => $history_quotes[$key]['id']]);
            foreach ($disease_biospecimen_types as $index => $disease_biospecimen_type) {
                $biospecimen_type = App::call()->biospecimenTypeRepository->getOne($disease_biospecimen_type['biospecimen_type_id']);
                $disease_biospecimen_types[$index]['biospecimen_type'] = $biospecimen_type['biospecimen_type'];
                $disease_biospecimen_types[$index]['biospecimen_type_russian'] = $biospecimen_type['biospecimen_type_russian'];
                $mod = App::call()->sampleModRepository->getOne($disease_biospecimen_type['mod_id']);
                $disease_biospecimen_types[$index]['mod'] = $mod['modification'];
                $disease_biospecimen_types[$index]['enabled'] = true;
                $disease_biospecimen_types[$index]['db_id'] = $disease_biospecimen_type['id'];
            }
            $history_quotes[$key]['samples_table'] = $disease_biospecimen_types;
            $doctor_payments = [];
            foreach ($quoteDoctorsById as $QDid => $quoteDoctor) {
                if ($quoteDoctor['quote_id'] === $quote['id']) {
                    $doctor = $doctorsById->{$quoteDoctor['doc_id']};
                    $quoteDoctorsById->{$QDid}['doc_name'] = "{$doctor['lastname']} {$doctor['firstname']} {$doctor['patronymic']}";
                    $doctor_payments[] = $quoteDoctorsById->{$QDid};
                }
            }
            $history_quotes[$key]['doctor_payments'] = $doctor_payments;
            $quote_creation_datetime_frags = explode(' ', $quote['created_at']);
            $creation_date_frags = explode('-', $quote_creation_datetime_frags[0]);
            $history_quotes[$key]['created_at'] = "{$creation_date_frags[2]}.{$creation_date_frags[1]}.{$creation_date_frags[0]}";
        }


        $worksheets = App::call()->worksheetsRepository->getCountByProjectAndGroupUserName($idFR);
        $role = App::call()->session->getSession('role_id');

        $orderStatus = [];
        $nbs_orderStatus = App::call()->ordersStatusRepository->getAll();
        for ($i = 0; $i < count($nbs_orderStatus); $i++) {
            $orderStatus[] = $nbs_orderStatus[$i];
        }
        $inventory = App::call()->worksheetsInvertoryRepository->getByProj($idFR);
        $laboratory = App::call()->worksheetsLaboratoryRepository->getByProj($idFR);


        // Проверка если у нас не создано коммерческое предложение его нужно создать -- НАЧАЛО
        $checkOffer = App::call()->offerRepository->getWHERE(['proj_id' => $idFR]);

        $id = $checkOffer[0]['id'];
        $offer = App::call()->offerRepository->getObject($id);
        if (!$offer) {
            $offer = new Offer();
            $date = DATE('Y-m-d');
            $offer->proj_id = $idFR;
            $offer->user_id = $fr['answering_id'];
            $offer->date_offer = DATE('Y-m-d');
            $offer->date_valid = date('Y-m-d', strtotime('+3 month', strtotime($date)));
            $offer->courier_id = 1;
            $offer->scripts_staff_id = $fr['attention_to'];
            App::call()->offerRepository->save($offer);
        }
        // Конец создания коммерческого предложения

        // Проверка если у нас не создано Русское коммерческое предложение его нужно создать -- НАЧАЛО
        $checkOfferRu = App::call()->offerRuRepository->getWhere(['proj_id' => $idFR]);

        $idRu = $checkOfferRu[0]['id'];
        $offer_ru = App::call()->offerRuRepository->getObject($idRu);
        if (!$offer_ru) {
            $offer_ru = new OfferRu();
            $date = DATE('Y-m-d');
            $offer_ru->proj_id = $idFR;
            $offer_ru->date_offer = DATE('Y-m-d');
            $offer_ru->date_valid = date('Y-m-d', strtotime('+3 month', strtotime($date)));
            $offer_ru->courier_id = 1;
            $offer_ru->scripts_staff_id = $fr['attention_to'];
            App::call()->offerRuRepository->save($offer_ru);
        }
        // Конец создания Русского коммерческого предложения

        // Проверка если у нас не создано коммерческое предложение макак его нужно создать -- НАЧАЛО
        $checkApeOffer = App::call()->offerApeRepository->getWHERE(['proj_id' => $idFR]);
        $idApe = $checkApeOffer[0]['id'];
        $offerApe = App::call()->offerApeRepository->getObject($idApe);
        if (!$offerApe) {
            $offerApe = new OfferApe();
            $date = DATE('Y-m-d');
            $offerApe->proj_id = $idFR;
            $offerApe->date_offer =
            $offerApe->date_valid = date('Y-m-d', strtotime('+3 month', strtotime($date)));;
            $offerApe->courier_id = 1;
            $offerApe->scripts_staff_id = $fr['attention_to'];
            App::call()->offerApeRepository->save($offerApe);
        }
        // Конец создания коммерческого предложения макак


        $attention_to = $fr['attention_to'];
        $script_id = $fr['fr_script_id'];
        $company = App::call()->companyRepository->getOne($script_id);
        $currency = $company['currency'];

        if ($currency == '') {
            $currency = 'USD';
        } else {
            $currency = App::call()->currencyRepository->getOne($currency);
            $currency = $currency['currency'];
        }
        $start = microtime(true);
        $approval_tickets = App::call()->newTicketsRepository->getWhere(['order_id' => $idFR]);
        $today = date('Y-m-d');

        $triggers_all = App::call()->offerStatusTriggerRepository->getAllJoined($today);
        $tasks = [];
        foreach ($triggers_all as $task) {
            $task_date_notime = explode(' ', $task['task_date']);
            $task_date_frags = explode('-', $task_date_notime[0]);
            $task['task_date'] = $task_date_frags[2] . '.' . $task_date_frags[1] . '.' . $task_date_frags[0];
            $tasks[] = $task;
        }

        $time = microtime(true) - $start;
        //echo $time;


        $st = $fr['status_manager'];

        $department_id = App::call()->session->getSession('department_id');

        if ($department_id == 4) {

        } else if ($role == 3 and $st != 2 and $st != 3) {
            echo $this->renderTemplate('404', $params = [
                'error' => 'Статус заявки не позволяет ее просмотр'
            ]);
            die();
        }

        // Quote project data
        $offer = App::call()->offerRepository->getWhere(['proj_id' => $idFR]);
        $offer_items = App::call()->offerItemRepository->getWhere(['offer_id' => $offer[0]['id']]);
        foreach ($offer_items as $i => $offer_item) {
            $timelines = App::call()->offerItemTimelineRepository->getWhere(['offer_item_id' => $offer_item['id']]);
            foreach ($timelines as $key => $el) {
                $sample_name = App::call()->biospecimenTypeRepository->getOne($el['sample_id']);
                if ($sample_name)
                    $timelines[$key]['sample_name'] = $sample_name['biospecimen_type'];
                else
                    $timelines[$key]['sample_name'] = '';
                $mod_name = App::call()->diseasesBiospecimenTypesRepository->getOne($el['sample_id']);
                if ($mod_name)
                    $timelines[$key]['mod_name'] = $sample_name['mod_id'];
                else
                    $timelines[$key]['mod_name'] = '';
            }
            $offer_items[$i]['timelines'] = $timelines;
            $offer_items[$i]['inclusion_criteria'] = App::call()->offerItemInclusionCriteriaRepository->getWhere(['offer_item_id' => $offer_item['id']]);
            $offer_items[$i]['exclusion_criteria'] = App::call()->offerItemExclusionCriteriaRepository->getWhere(['offer_item_id' => $offer_item['id']]);
            $offer_items[$i]['clinical_information'] = App::call()->offerItemClinicalInformationRepository->getWhere(['offer_item_id' => $offer_item['id']]);
            $offer_items[$i]['list_of_samples'] = App::call()->offerItemListOfSamplesRepository->getWhere(['offer_item_id' => $offer_item['id']]);
        }
        $offer_items = ['result' => $offer_items];

        // QuoteRu project data
        $offer_ru = App::call()->offerRuRepository->getWhere(['proj_id' => $idFR]);
        $offer_ru_items = App::call()->offerRuItemRepository->getWhere(['offer_ru_id' => $offer_ru[0]['id']]);
        foreach ($offer_ru_items as $i => $offer_ru_item) {
            $timelines = App::call()->offerRuItemTimelineRepository->getWhere(['offer_ru_item_id' => $offer_ru_item['id']]);
            foreach ($timelines as $key => $el) {
                $sample_name = App::call()->biospecimenTypeRepository->getOne($el['sample_id']);
                if ($sample_name) {
                    $timelines[$key]['sample_name'] = $sample_name['biospecimen_type'];
                    $timelines[$key]['sample_name_rus'] = $sample_name['biospecimen_type_russian'];
                } else
                    $timelines[$key]['sample_name'] = '';
                $mod_name = App::call()->diseasesBiospecimenTypesRepository->getOne($el['sample_id']);
                if ($mod_name)
                    $timelines[$key]['mod_name'] = $sample_name['mod_id'];
                else
                    $timelines[$key]['mod_name'] = '';
            }
            $offer_ru_items[$i]['timelines'] = $timelines;
            $offer_ru_items[$i]['inclusion_criteria'] = App::call()->offerRuItemInclusionCriteriaRepository->getWhere(['offer_ru_item_id' => $offer_ru_item['id']]);
            $offer_ru_items[$i]['exclusion_criteria'] = App::call()->offerRuItemExclusionCriteriaRepository->getWhere(['offer_ru_item_id' => $offer_ru_item['id']]);
            $offer_ru_items[$i]['clinical_information'] = App::call()->offerRuItemClinicalInformationRepository->getWhere(['offer_ru_item_id' => $offer_ru_item['id']]);
            $offer_ru_items[$i]['list_of_samples'] = App::call()->offerRuItemListOfSamplesRepository->getWhere(['offer_ru_item_id' => $offer_ru_item['id']]);
        }
        $offer_ru_items = ['result' => $offer_ru_items];

        $script = [];
        $fr_scripts = App::call()->companyRepository->getAllSort();
        for ($i = 0; $i < count($fr_scripts); $i++) {
            $script[] = $fr_scripts[$i];
        }
        $biospecimen_types = App::call()->biospecimenTypeRepository->getAll();

        echo $this->render('orders/ordersInfo', [
            'fr' => $fr,
            'company' => $fr_scripts,
            'status' => $status,
            'users' => $users,
            'files' => $files,
            'role' => $role,
            'history_quotes' => $history_quotes,
            'quotes' => $quotes,
            'orstatus' => $orderStatus,
            'worksheets' => $worksheets,
            'inventory' => $inventory,
            'laboratory' => $laboratory,
            'department_id' => $department_id,

            'proj_id' => $idFR,
            'script_id' => $script_id,
            'attention_to' => $attention_to,
            'currency' => $currency,

            'user_id' => $user_id,
//            'approvals' => $approvals,
            'approval_tickets' => $approval_tickets,
            'tasks' => $tasks,/*,
            'status_names' => $status_names*/
            'diseases' => $diseases,
            'order_diseases' => $order_diseases,
            'diseases_biospecimen_types' => $diseases_biospecimen_types,
            'order_disease_options' => json_encode($order_diseases, JSON_UNESCAPED_UNICODE),
            'order_diseases_biospecimen' => json_encode($diseases_biospecimen_types, JSON_UNESCAPED_UNICODE),
            'order_diseases_biospecimen_rus' => json_encode($diseases_biospecimen_types_rus, JSON_UNESCAPED_UNICODE),
            'offer' => json_encode($offer[0], JSON_UNESCAPED_UNICODE),
            'offer_items' => json_encode($offer_items, JSON_UNESCAPED_UNICODE),
            'offer_ru' => json_encode($offer_ru[0], JSON_UNESCAPED_UNICODE),
            'offer_ru_items' => json_encode($offer_ru_items, JSON_UNESCAPED_UNICODE),
            'script' => $script,
            'biospecimen_types' => $biospecimen_types
        ]);
    }

    public function actionSave()
    {
        //   Переменная для обработки ошибок
        $result_text = 1;

        if (isset(App::call()->request->getParams()['proj_id'])) {
            $proj_id = App::call()->request->getParams()['proj_id'];
            $order = App::call()->ordersRepository->getObject($proj_id);
            $orderOld = App::call()->ordersRepository->getObject($proj_id);
        } else {
            $order = new Orders();
            $orderOld = new Orders();
        }

        if (isset(App::call()->request->getParams()['fileQouteSend'])) {
            $fileQouteSend = App::call()->request->getParams()['fileQouteSend'];
            $order->file_qoute_send = $fileQouteSend;
        }

        if (isset(App::call()->request->getParams()['fr_date'])) {
            $fr_date = App::call()->request->getParams()['fr_date'];
            $order->fr_date = $fr_date;
        }

        if (isset(App::call()->request->getParams()['linked_fr_id'])) {
            $linked_fr_id = App::call()->request->getParams()['linked_fr_id'];
            $order->linked_fr_id = $linked_fr_id;
        }

        if (isset(App::call()->request->getParams()['communication_date'])) {
            $communication_date = App::call()->request->getParams()['communication_date'];
            if ($communication_date == '_') {
                $communication_date = null;
            }
            $order->communication_date = $communication_date;
        }

        if (isset(App::call()->request->getParams()['communication_comment'])) {
            $communication_comment = App::call()->request->getParams()['communication_comment'];
            $order->communication_comment = $communication_comment;
        }

        if (isset(App::call()->request->getParams()['status_client'])) {
            if ($order->status_client !== App::call()->request->getParams()['status_client']) {
                $status_client = App::call()->request->getParams()['status_client'];
                $order->status_client = $status_client;
            }
        }

        if (isset(App::call()->request->getParams()['feas_russian'])) {
            $feas_russian = App::call()->request->getParams()['feas_russian'];
            if ($feas_russian != '') {
                $order->feas_russian = $feas_russian;
            }

        }

        if (isset(App::call()->request->getParams()['status_manager'])) {
            $status_manager = App::call()->request->getParams()['status_manager'];
            if ($status_manager == '2') {
                if ($order->feas_russian != '' && $order->feas_russian != 'null') {
                    $order_diseases = App::call()->orderDiseasesRepository->getWhere(['order_id' => $order->proj_id]);
                    if (count($order_diseases) > 0) {
                        $order->status_manager = $status_manager;
                    } else {
                        $result_text = 'Нет болезний' . PHP_EOL;
                    }
                } else {
                    $result_text = 'Не заполенно поле Request(Russian text)' . PHP_EOL;
                }
            } else {
                $order->status_manager = $status_manager;
            }
        }

        if (isset(App::call()->request->getParams()['status_manager'])) {
            $status_manager = App::call()->request->getParams()['status_manager'];
            if ($status_manager == '46') {
                if ($order->feas_russian != '' && $order->feas_russian != 'null') {
                    $order_diseases = App::call()->orderDiseasesRepository->getWhere(['order_id' => $order->proj_id]);
                    if (count($order_diseases) > 0) {
                        $order->status_manager = $status_manager;
                    } else {
                        $result_text = 'Нет болезний' . PHP_EOL;
                    }

                } else {
                    $result_text = 'Не заполенно поле Request(Russian text)' . PHP_EOL;
                }
            } else {
                $order->status_manager = $status_manager;
            }
        }

        if (isset(App::call()->request->getParams()['status_project'])) {
            $status_project = App::call()->request->getParams()['status_project'];
            $order->status_project = $status_project;
        }
        if (isset(App::call()->request->getParams()['status_lpo'])) {
            $status_lpo = App::call()->request->getParams()['status_lpo'];
            $order->status_lpo = $status_lpo;
        }
        if (isset(App::call()->request->getParams()['status_ved'])) {
            $status_ved = App::call()->request->getParams()['status_ved'];
            $order->status_ved = $status_ved;
        }
        if (isset(App::call()->request->getParams()['status_boss'])) {
            $status_boss = App::call()->request->getParams()['status_boss'];
            $order->status_boss = $status_boss;
        }
        if (isset(App::call()->request->getParams()['history_quotes_search'])) {
            $history_quotes_search = App::call()->request->getParams()['history_quotes_search'];
            if ($history_quotes_search != '') {
                $order->history_quotes_search = $history_quotes_search;
            }
        }
        if (isset(App::call()->request->getParams()['attention_to'])) {
            $attention_to = App::call()->request->getParams()['attention_to'];
            $order->attention_to = $attention_to;
        }

        if (isset(App::call()->request->getParams()['fr_script_id'])) {
            $fr_script_id = App::call()->request->getParams()['fr_script_id'];
            $order->fr_script_id = $fr_script_id;

            if (isset(App::call()->request->getParams()['proj_id'])) {
                $script_number = App::call()->request->getParams()['script_number'];
            } else {
                $script_number_array = App::call()->ordersRepository->maxScriptNum($fr_script_id);
                $script_number = $script_number_array['script_number'] + 1;
            }

            $order->script_number = $script_number;
            $company = App::call()->companyRepository->getObject($fr_script_id);
            $last_script_num = $company->script . '-' . $script_number;
            $company->last_script_num = $script_number;
            App::call()->companyRepository->save($company);
            $order->internal_id = $last_script_num;

        }

        if (isset(App::call()->request->getParams()['project_name'])) {
            $project_name = App::call()->request->getParams()['project_name'];
            $order->project_name = $project_name;
        }

        if (isset(App::call()->request->getParams()['comments'])) {
            $comments = App::call()->request->getParams()['comments'];
            if ($comments != '') {
                $order->comments = $comments;
            }
        }

        if (isset(App::call()->request->getParams()['project_details'])) {
            $project_details = App::call()->request->getParams()['project_details'];
            if ($project_details != '') {
                $order->project_details = $project_details;
            }

        }

        if (isset(App::call()->request->getParams()['currency'])) {
            $currency = App::call()->request->getParams()['currency'];
            $order->currency = $currency;
        }

        if (isset(App::call()->request->getParams()['clinical_inclusion'])) {
            $clinical_inclusion = App::call()->request->getParams()['clinical_inclusion'];
            $order->clinical_inclusion = $clinical_inclusion;
        }

        if (isset(App::call()->request->getParams()['unformal_quote'])) {
            $unformal_quote = App::call()->request->getParams()['unformal_quote'];
            if ($unformal_quote != '') {
                $order->unformal_quote = $unformal_quote;

                if ($orderOld->unformal_quote !== $unformal_quote) {
                    $log = new LogChange();
                    $log->user_id = App::call()->session->getSession('user_id');
                    $log->proj_id = App::call()->request->getParams()['proj_id'];
                    $log->info = $unformal_quote;
                    $log->input_type = 'unformal_quote';
                    App::call()->logChangeRepository->save($log);
                }
            }


        }

        if (isset(App::call()->request->getParams()['quote_date'])) {
            $quote_date = App::call()->request->getParams()['quote_date'];
            $order->quote_date = $quote_date;
        }

        if (isset(App::call()->request->getParams()['quotes_from_managers'])) {
            $quotes_from_managers = App::call()->request->getParams()['quotes_from_managers'];
            if ($quotes_from_managers != '') {
                $order->quotes_from_managers = $quotes_from_managers;
            }

        }

        if (isset(App::call()->request->getParams()['answering_id'])) {
            $answering_id = App::call()->request->getParams()['answering_id'];
            $order->answering_id = $answering_id;
        }

        if (isset(App::call()->request->getParams()['external_id'])) {
            $external_id = App::call()->request->getParams()['external_id'];
            $order->external_id = $external_id;
        }

        if (isset(App::call()->request->getParams()['deadline_post'])) {
            $deadline_post = App::call()->request->getParams()['deadline_post'];
            $order->deadline_post = $deadline_post;
        }

        if (isset(App::call()->request->getParams()['deadline_quote'])) {
            $deadline_quote = App::call()->request->getParams()['deadline_quote'];
            $order->deadline_quote = $deadline_quote;
        }

        //  Смена даты проекта квоты при установки статусов 44 и 45
        $check_status_boss =App::call()->request->getParams()['status_boss'];
        if ($check_status_boss == 42 OR $check_status_boss == 43 OR $check_status_boss == 44 OR $check_status_boss == 45) {
            if ($orderOld->status_boss != '42' AND  $orderOld->status_boss != '43' AND $orderOld->status_boss != '44' AND $orderOld->status_boss != '45') {

                if($order->quote_type == 'standart') {
                    $offer = App::call()->offerRepository->getWhere(['proj_id' => $proj_id]);
                    $offer_change = App::call()->offerRepository->getObject($offer[0]['id']);
                    $offer_change->date_offer = DATE('Y-m-d');
                    $offer_change->user_id = $order->answering_id;
                    $result =App::call()->offerRepository->save($offer_change);

                } else if ($order->quote_type == 'russian' OR $order->quote_type == 'russian_mod') {
                    $offer = App::call()->offerRuRepository->getWhere(['proj_id' => $proj_id]);
                    $offer_change = App::call()->offerRuRepository->getObject($offer[0]['id']);
                    $offer_change->date_offer = DATE('Y-m-d');
                    $offer_change->user_id = $order->answering_id;
                    $result = App::call()->offerRuRepository->save($offer_change);

                } else if ($order->quote_type = 'ape') {
                    $offer = App::call()->offerApeRepository->getWhere(['proj_id' => $proj_id]);
                    $offer_change = App::call()->offerApeRepository->getObject($offer[0]['id']);
                    $offer_change->date_offer = DATE('Y-m-d');
                    $offer_change->user_id = $order->answering_id;
                    $result = App::call()->offerApeRepository->save($offer_change);
                }
            }
        }

        if (isset(App::call()->request->getParams()['status_client'])) {
            // Квотировано
            if (App::call()->request->getParams()['status_client'] == 27) {
                if ($orderOld->status_client != '27') {
                    // Quoted
                    $order->fr_status = 3;
                    $order->history_quotes_search = 1;
                    $day = date('Y-m-d');
                    $newDate = date('Y-m-d', strtotime($day . "+7 days"));
                    $order->communication_date = $newDate;
                    $order->communication_comment = 'created at I-BIOSplatform auto';
                }


            }
            // Запрос отменен
            if (App::call()->request->getParams()['status_client'] == 28) {
                // Cancelled
                $order->fr_status = 6;
            }
            // Выполнение запроса невозможно
            if (App::call()->request->getParams()['status_client'] == 29) {
                // Unfeasible
                $order->fr_status = 5;
            }
            // РО получено
            if (App::call()->request->getParams()['status_client'] == 33) {
                if ($orderOld->status_client != '33') {
                    // PO received
                    $order->fr_status = 4;
                    $order->history_quotes_search = 1;
                    // Проверка на создание проекта
                    $po = App::call()->poRepository->GetOrdersOne($proj_id);
                    if (!isset($po['proj_id'])) {
                        // Создание поекта
                        $PO = new Po();
                        $PO->proj_id = $proj_id;
                        $PO->fr_date = DATE("Y-m-d");
                        $PO->internal_id = $orderOld->internal_id;
                        $PO->external_id = $orderOld->external_id;
                        $PO->cust_id = $orderOld->cust_id;
                        $PO->project_name = $orderOld->project_name;
                        $PO->clinical_inclusion = $orderOld->clinical_inclusion;
                        $PO->clinical_exclusion = $orderOld->clinical_exclusion;
                        $PO->donor_info = $orderOld->donor_info;
                        $PO->quote_date = $orderOld->quote_date;
                        $PO->currency = $orderOld->currency;
                        $PO->project_details_old = $orderOld->project_details_old;
                        $PO->other_requirements = $orderOld->other_requirements;
                        $PO->project_details = $orderOld->project_details;
                        $PO->quote_details = $orderOld->quote_details;
                        $PO->quotes_site_managers = $orderOld->quotes_site_managers;
                        $PO->old_status = $orderOld->old_status;
                        $PO->po_upload_url = $orderOld->po_upload_url;
                        $PO->fr_status = $orderOld->fr_status;
                        $PO->fr_status_date = $orderOld->fr_status_date;
                        $PO->linked_fr_id = $orderOld->linked_fr_id;
                        $PO->manager_quotes = $orderOld->manager_quotes;
                        $PO->feas_russian = $orderOld->feas_russian;
                        $PO->quote_upload_url = $orderOld->quote_upload_url;
                        $PO->fr_script_id = $orderOld->fr_script_id;
                        $PO->attention_to = $orderOld->attention_to;
                        $PO->script_number = $orderOld->script_number;
                        $PO->comments = $orderOld->comments;
                        $PO->quotes_from_managers = $orderOld->quotes_from_managers;
                        $PO->unformal_quote = $orderOld->unformal_quote;
                        $PO->request_type = $orderOld->request_type;
                        $PO->purchase_order = $orderOld->purchase_order;
                        $PO->quote_price = $orderOld->quote_price;
                        $PO->answering_id = $orderOld->answering_id;
                        $PO->priority_id = $orderOld->priority_id;
                        $PO->deadline = $orderOld->deadline;
                        $PO->deleted = $orderOld->deleted;
                        $PO->deadline_post = $orderOld->deadline_post;
                        $PO->deadline_post = $orderOld->deadline_quote;
                        $PO->history_quotes_search = $orderOld->history_quotes_search;
                        $PO->status_manager = $orderOld->status_manager;
                        $PO->status_project = $orderOld->status_project;
                        $PO->status_lpo = $orderOld->status_lpo;
                        $PO->status_ved = $orderOld->status_ved;
                        $PO->status_boss = $orderOld->status_boss;
                        $PO->status_client = $orderOld->status_client;
                        $PO->margin = $orderOld->margin;
                        $PO->history_po = $orderOld->history_po;
                        $PO->historical_data = $orderOld->historical_data;
                        $PO->quote_type = $orderOld->quote_type;
                        $PO->communication_date = $orderOld->communication_date;
                        $PO->communication_comment = $orderOld->communication_comment;
                        App::call()->poRepository->save($PO);
                        $order_diseases = App::call()->orderDiseasesRepository->getWhere(['order_id' => $proj_id]);
                        foreach ($order_diseases as $order_disease) {
                            $po_disease = new PoDisease($proj_id, $order_disease['disease_id']);
                            App::call()->poDiseaseRepository->save($po_disease);
                        }
                        $diseases_biospecimen_types = App::call()->diseasesBiospecimenTypesRepository->getWhere(['order_id' => $proj_id]);
                        foreach ($diseases_biospecimen_types as $diseases_biospecimen_type) {
                            $po_disease_sample_mod = new PoDiseaseSampleMod($proj_id, $diseases_biospecimen_type['disease_id'], $diseases_biospecimen_type['biospecimen_type_id'], $diseases_biospecimen_type['mod_id']);
                            App::call()->poDiseaseSampleModRepository->save($po_disease_sample_mod);
                        }
                    }
                }
            }
        }
        // Отправлено менеджерам
        if (isset(App::call()->request->getParams()['status_manager'])) {
            if (App::call()->request->getParams()['status_manager'] == 2) {
                // Sent to managers
                $order->fr_status = 1;
            }
        }


        // Проект квоты отправлен на одобрение
        if (isset(App::call()->request->getParams()['status_boss'])) {
            // Проект квоты отправлен на одобрение
            if (App::call()->request->getParams()['status_boss'] == 22) {
                // Ремайндер послан на одобрение
                $order->fr_status = 10;
            }
            // Проект квоты нуждается в доработке
            if (App::call()->request->getParams()['status_boss'] == 23) {
                // Квота нуждается в доработке
                $order->fr_status = 13;
            }
            // Проект квоты одобрен
            if (App::call()->request->getParams()['status_boss'] == 24) {
                // Quoted
                $order->fr_status = 3;
            }
//            if (App::call()->request->getParams()['status_boss'] == 42) {
//                new OfferApproval($order, 56); // Анисимов
//            }
            // target_id = 1 // Создание ремайдеров на Капустина Глеба
            if ($orderOld->status_boss != 40 && App::call()->request->getParams()['status_boss'] == 40) {
                $text = "Добрый день," . PHP_EOL . "Необходимо предоставить ответ на запрос № 
                    <a href='http://crm.i-bios.com/orders/info/?idFR={$order->proj_id}'>{$order->internal_id} ($order->project_name)</a>";
                $tick = new NewTickets($order->answering_id, 2, "Заявка {$order->proj_id} ({$order->internal_id})", $text, 0, 0, $order->proj_id, 0);
                App::call()->newTicketsRepository->save($tick);
            } else if ($orderOld->status_boss != 42 && App::call()->request->getParams()['status_boss'] == 42) {
                $text = "Добрый день," . PHP_EOL . "Необходимо предоставить ответ на запрос № 
                    <a href='http://crm.i-bios.com/orders/info/?idFR={$order->proj_id}'>{$order->internal_id} ($order->project_name)</a>";
                $tick = new NewTickets($order->answering_id, 56, "Заявка {$order->proj_id} ({$order->internal_id})", $text, 0, 0, $order->proj_id, 0);
                $tick_id = App::call()->newTicketsRepository->save($tick);
                $mailSend = new Mail();
                $mailSend->email = 'sergey.anisimov@i-bios.com';
                $mailSend->subject = "Необходимо предоставить ответ на запрос № {$order->internal_id}";
                $mailSend->text_mail = "Добрый день," . PHP_EOL . "Необходимо предоставить ответ на запрос № 
                    <a href='http://crm.i-bios.com/newTickets/view/?id=$tick_id'>{$order->internal_id}</a>";
                App::call()->mailRepository->save($mailSend);
            } else if ($orderOld->status_boss != 43 && App::call()->request->getParams()['status_boss'] == 43) {
                $text = "Добрый день," . PHP_EOL . "Необходимо предоставить ответ на запрос № 
                    <a href='http://crm.i-bios.com/orders/info/?idFR={$order->proj_id}'>{$order->internal_id}  ($order->project_name)</a>";
                $tick = new NewTickets($order->answering_id, 56, "Заявка {$order->proj_id} ({$order->internal_id})", $text, 0, 0, $order->proj_id, 1);
                $tick_id = App::call()->newTicketsRepository->save($tick);
                $mailSend = new Mail();
                $mailSend->email = 'sergey.anisimov@i-bios.com';
                $mailSend->subject = "Необходимо предоставить ответ на запрос № {$order->internal_id}";
                $mailSend->text_mail = "Добрый день," . PHP_EOL . "Необходимо предоставить ответ на запрос № 
                    <a href='http://crm.i-bios.com/newTickets/view/?id=$tick->id'>{$order->internal_id}</a>";
                App::call()->mailRepository->save($mailSend);
            } else if (($orderOld->status_boss != 44 && App::call()->request->getParams()['status_boss'] == 44) ||
                ($orderOld->status_boss != 45 && App::call()->request->getParams()['status_boss'] == 45)) {
                $text = "Добрый день," . PHP_EOL . "Необходимо предоставить ответ на запрос № 
                    <a href='http://crm.i-bios.com/orders/info/?idFR={$order->proj_id}'>{$order->internal_id}  ($order->project_name)</a>";
                $priority = App::call()->request->getParams()['status_boss'] == 45 ? 1 : 0;
                $tick = new NewTickets($order->answering_id, 4, "Заявка {$order->proj_id} ({$order->internal_id})", $text, 0, 0, $order->proj_id, $priority);
                $tick_id = App::call()->newTicketsRepository->save($tick);
                $mailSendTime = date('Y-m-d H:i:s');
                if (!$priority && date('H:i') > '18:30') {
                    $mailSendTime = date('Y-m-d 09:00:00', strtotime(' +1 day'));
                } else if (!$priority && date('H:i') < '09:00') {
                    $mailSendTime = date('Y-m-d 09:00:00');
                }
                $mailSend = new Mail();
                $mailSend->email = 'oleg.granstrem@i-bios.com';
                $mailSend->subject = "Reminder № {$order->internal_id}";
                $mailSend->text_mail = "Добрый день," . PHP_EOL . "Необходимо предоставить ответ на запрос № 
                    <a href='http://crm.i-bios.com/newTickets/view/?id=$tick_id'>{$order->internal_id}  ($order->project_name)</a>";
                $mailSend->send_time = $mailSendTime;
                $mailSend->send = 'NO';
                App::call()->mailRepository->save($mailSend);
                $mailSend2 = new Mail();
                $mailSend2->email = 'sergey.anisimov@i-bios.com';
                $mailSend2->subject = "Reminder № {$order->internal_id}";
                $mailSend2->text_mail = "Добрый день," . PHP_EOL . "Необходимо предоставить ответ на запрос № 
                    <a href='http://crm.i-bios.com/newTickets/view/?id=$tick_id'>{$order->internal_id} ($order->project_name)</a>";
                $mailSend2->send_time = $mailSendTime;
                $mailSend2->send = 'NO';
                App::call()->mailRepository->save($mailSend2);
                $mailSend3 = new Mail();
                $mailSend3->email = 'vitali.proutski@i-bios.com';
                $mailSend3->subject = "Reminder № {$order->internal_id}";
                $mailSend3->text_mail = "Добрый день," . PHP_EOL . "Необходимо предоставить ответ на запрос № 
                    <a href='http://crm.i-bios.com/newTickets/view/?id=$tick_id'>{$order->internal_id} ($order->project_name)</a>";
                $mailSend3->send_time = $mailSendTime;
                $mailSend3->send = 'NO';
                App::call()->mailRepository->save($mailSend3);

                $user_id = App::call()->session->getSession('user_id');
                if($user_id == 38) {
                    $mailSend4 = new Mail();
                    $mailSend4->email = 'maksim.zvyagintsev@nbioservice.com';
                    $mailSend4->subject = "ЭЛИНА Reminder";
                    $mailSend4->text_mail = "Добрый день," . PHP_EOL . "Необходимо предоставить ответ на запрос № 
                    <a href='http://crm.i-bios.com/newTickets/view/?id=$tick_id'>{$order->internal_id} ($order->project_name)</a>";
                    $mailSend4->send_time = $mailSendTime;
                    $mailSend4->send = 'NO';
                    App::call()->mailRepository->save($mailSend4);
                }

            }
        }

        if (isset(App::call()->request->getParams()['margin'])) {
            $margin = App::call()->request->getParams()['margin'];
            if($margin == '') {
                // TODO изменение маржиналости не возможно на пустое значние
            } else {
                $order->margin = $margin;
            }
        }
        if (isset(App::call()->request->getParams()['quote_type'])) {
            $quote_type = App::call()->request->getParams()['quote_type'];
            $order->quote_type = $quote_type;
        }


        // Снятие приоритета
        if ($order->fr_status == 3
            or $order->fr_status == 5
            or $order->fr_status == 6
        ) {
            $order->priority_id = 0;
        }

        //  Генерация


        if (isset(App::call()->request->getParams()['deadline'])) {
            $deadline = App::call()->request->getParams()['deadline'];
            $order->deadline = $deadline;
        }

        if (isset(App::call()->request->getParams()['history_po'])) {
            $history_po = App::call()->request->getParams()['history_po'];

            if ($orderOld->history_po !== $history_po) {
                $log = new LogChange();
                $log->user_id = App::call()->session->getSession('user_id');
                $log->proj_id = App::call()->request->getParams()['proj_id'];
                $log->info = $history_po;
                $log->input_type = 'historical_po';
                App::call()->logChangeRepository->save($log);
            }


            $order->history_po = $history_po;
        }

        if (isset(App::call()->request->getParams()['historical_data'])) {
            $historical_data = App::call()->request->getParams()['historical_data'];
            if ($historical_data != '') {
                $order->historical_data = $historical_data;
            }
        }
        if (isset(App::call()->request->getParams()['priority'])) {
            $priority = App::call()->request->getParams()['priority'];
            $order->priority = $priority;
        }
        if (isset(App::call()->request->getParams()['lto_comment'])) {
            $lto_comment = App::call()->request->getParams()['lto_comment'];
            $order->lto_comment = $lto_comment;
        }
        if (isset(App::call()->request->getParams()['po_comment'])) {
            $po_comment = App::call()->request->getParams()['po_comment'];
            $order->po_comment = $po_comment;
        }

        $result = App::call()->ordersRepository->save($order);

        if (isset(App::call()->request->getParams()['status_project'])) {
            // Отправлен запрос на инвентори
            if (App::call()->request->getParams()['status_project'] == 7) {
                // Создаем задачу для проектного отдела
                if (isset(App::call()->request->getParams()['proj_id'])) {
                    $proj_id = App::call()->request->getParams()['proj_id'];
                } else {
                    $proj_id = $result;
                }
                $user_id = App::call()->session->getSession('user_id');

                $invertory = new WorksheetsInvertory();
                $invertory->proj_id = $proj_id;
                $invertory->user_id = $user_id;
                $invertory->sample = '0';
                $invertory->alias = '';
                $invertory->comments = '';
                $invertory->created_at = Date('Y-m-d H:m:i');
                $result_proj = App::call()->worksheetsInvertoryRepository->save($invertory);

                // Отправляем оповещение проектному  отделу
                if ($result_proj > 1) {
                    $department_id = 3;
                    $subject = "Требуется Inventory {$order->internal_id}";
                    $text = "Добрый день,
                    Необходимо предоставить Inventory по запросу № <a href='http://crm.i-bios.com/orders/info/?idFR={$order->proj_id}'>{$order->internal_id}</a>     
                    {$order->project_details}
                    ";
                    App::call()->mailRepository->sendToDepartament($department_id, $subject, $text);
                }

            }
        }

        if (isset(App::call()->request->getParams()['status_lpo'])) {
            // Если статус Требуется информация от ЛТО


            if (App::call()->request->getParams()['status_lpo'] == 12) {
                // Создаем задачу для отдела лабаратории

                if (isset(App::call()->request->getParams()['proj_id'])) {
                    $proj_id = App::call()->request->getParams()['proj_id'];
                } else {
                    $proj_id = $result;
                }

                // Проверям какой сейчас статус
                if ($orderOld->status_lpo != '12') {
                    // Нужно проверить стояли статус ранее
                    $count = App::call()->ordersStatusActionsRepository->getStatusCheck($proj_id, 12);
                    $total = $count[0]['total']; // кол-во

                    // Если кол-во больше 0 то нужно задача для лабораториии уже создана и нужно изменить ее сатус
                    if ($total > 0) {

                        $wl = App::call()->worksheetsLaboratoryRepository->checkByProjId($proj_id);
                        $wlId = $wl['id'];
                        $wlOrder = App::call()->worksheetsLaboratoryRepository->getObject($wlId);
                        $wlOrder->sample = '0';
                        App::call()->worksheetsLaboratoryRepository->save($wlOrder);
                        // Если нет создаем новую задачу
                    } else {
                        $user_id = App::call()->session->getSession('user_id');

                        $laboratory = new WorksheetsLaboratory();
                        $laboratory->proj_id = $proj_id;
                        $laboratory->user_id = $user_id;
                        $laboratory->sample = '0';
                        $laboratory->material_intake = '0';
                        $laboratory->material_intake_text = '';
                        $laboratory->material_intake_num = '0';
                        $laboratory->expendable_materials = '0';
                        $laboratory->expendable_materials_text = '';
                        $laboratory->expendable_materials_num = '0';
                        $laboratory->financial_expenses = '0';
                        $laboratory->financial_expenses_text = '';
                        $laboratory->financial_expenses_num = '0';

                        $laboratory->comments = '';
                        $laboratory->created_at = Date('Y-m-d H:m:i');
                        $result_lab = App::call()->worksheetsLaboratoryRepository->save($laboratory);

                        if ($result_lab > 1) {
                            $department_id = 4;
                            $subject = "Требуется информация от ЛТО по запросу  номер {$order->internal_id}";
                            $text = "Добрый день,
                    Необходимо предоставить ответ на запрос № <a href='http://crm.i-bios.com/orders/info/?idFR={$order->proj_id}'>{$order->internal_id}</a>     
                    {$order->project_details}
                    ";
                            App::call()->mailRepository->sendToDepartament($department_id, $subject, $text);
                        }
                    }
                }


            }
        }

        // Блок для проверки создавать ли  задачу менеджеру
        $prog_log = '';
        if (isset(App::call()->request->getParams()['proj_id'])) {
            $prog_log = App::call()->request->getParams()['proj_id'];
            if ($orderOld->status_manager != 2 AND $order->status_manager == 2) {
                App::call()->worksheetsRepository->generateOnebyOneOrders($prog_log);
            } elseif ($orderOld->status_manager != 46 AND $order->status_manager == 46) {
                // Приматы
                App::call()->worksheetsRepository->generateOnebyOneOrdersPrimat($prog_log);
            }
        } else {
            if ($result > 0) {
                $prog_log = $result;
                if ($orderOld->status_manager != 2 AND $order->status_manager == 2) {
                    App::call()->worksheetsRepository->generateOnebyOneOrders($result);
                    // Приматы
                } elseif ($orderOld->status_manager != 46 AND $order->status_manager == 46) {
                    App::call()->worksheetsRepository->generateOnebyOneOrdersPrimat($result);

                }
            }

        }


        if (isset(App::call()->request->getParams()['status_client'])) {
            if ($orderOld->status_client !== App::call()->request->getParams()['status_client']) {
                $log = new OrdersStatusActions();
                $log->proj_id = $prog_log;
                $log->department_id = 7;
                $log->orders_status_id = App::call()->request->getParams()['status_client'];
                $log->user_id = App::call()->session->getSession('user_id');
                App::call()->ordersStatusActionsRepository->save($log);
            }

        }


        if (isset(App::call()->request->getParams()['status_manager'])) {
            if ($orderOld->status_manager !== App::call()->request->getParams()['status_manager']) {
                $log = new OrdersStatusActions();
                $log->proj_id = $prog_log;
                $log->department_id = 2;
                $log->orders_status_id = App::call()->request->getParams()['status_manager'];
                $log->user_id = App::call()->session->getSession('user_id');
                App::call()->ordersStatusActionsRepository->save($log);
            }
        }


        if (isset(App::call()->request->getParams()['status_project'])) {
            if ($orderOld->status_project !== App::call()->request->getParams()['status_project']) {
                $log = new OrdersStatusActions();
                $log->proj_id = $prog_log;
                $log->department_id = 3;
                $log->orders_status_id = App::call()->request->getParams()['status_project'];
                $log->user_id = App::call()->session->getSession('user_id');
                App::call()->ordersStatusActionsRepository->save($log);
            }
        }

        if (isset(App::call()->request->getParams()['status_lpo'])) {
            if ($orderOld->status_lpo != App::call()->request->getParams()['status_lpo']) {
                $log = new OrdersStatusActions();
                $log->proj_id = $prog_log;
                $log->department_id = 4;
                $log->orders_status_id = App::call()->request->getParams()['status_lpo'];
                $log->user_id = App::call()->session->getSession('user_id');
                App::call()->ordersStatusActionsRepository->save($log);
            }
        }

        if (isset(App::call()->request->getParams()['status_ved'])) {
            if ($orderOld->status_ved !== App::call()->request->getParams()['status_ved']) {
                $log = new OrdersStatusActions();
                $log->proj_id = $prog_log;
                $log->department_id = 5;
                $log->orders_status_id = App::call()->request->getParams()['status_ved'];
                $log->user_id = App::call()->session->getSession('user_id');
                App::call()->ordersStatusActionsRepository->save($log);
            }
        }

        if (isset(App::call()->request->getParams()['status_boss'])) {
            if ($orderOld->status_boss !== App::call()->request->getParams()['status_boss']) {
                $log = new OrdersStatusActions();
                $log->proj_id = $prog_log;
                $log->department_id = 6;
                $log->orders_status_id = App::call()->request->getParams()['status_boss'];
                $log->user_id = App::call()->session->getSession('user_id');
                App::call()->ordersStatusActionsRepository->save($log);
            }

        }


        if (isset(App::call()->request->getParams()['fr_status'])) {
            if ($orderOld->fr_status !== App::call()->request->getParams()['fr_status']) {
                $log = new OrdersStatusActions();
                $log->proj_id = $prog_log;
                $log->department_id = '0';
                $log->orders_status_id = App::call()->request->getParams()['fr_status'];
                $log->user_id = App::call()->session->getSession('user_id');
                App::call()->ordersStatusActionsRepository->save($log);
            }

        }


        if (isset(App::call()->request->getParams()['proj_id'])) {
            $proj_id = App::call()->request->getParams()['proj_id'];
            $order = App::call()->ordersRepository->getObject($proj_id);
            $status_date = App::call()->ordersStatusActionsRepository->getStatusDate($proj_id);
            if (count($status_date) > 0) {
                $fr_status_date = $status_date[0]['statusdate'];
                $fr_status_date = date("Y-m-d", strtotime($fr_status_date));
                $order->fr_status_date = $fr_status_date;
                App::call()->ordersRepository->save($order);
            }
        }
        if ($result_text == 1) {
            $result_text = $result;
        }
        echo $result_text;

    }

    public function actionSaveFile()
    {
        $proj_id = App::call()->request->getParams()['proj_id'];
        $info = App::call()->request->getParams()['info'] ?? 'none';
        $fileUpload = App::call()->filesRepository->uploadFiles($proj_id, $info);
        echo $fileUpload;
    }

    public function actionChangePriority()
    {
        $proj_id = App::call()->request->getParams()['proj_id'];
        $priority = App::call()->request->getParams()['priority'];
        $order = App::call()->ordersRepository->getObject($proj_id);
        $order->priority_id = $priority;
        $result = App::call()->ordersRepository->save($order);
        echo $result;
    }

    public function actionCountMyPriority()
    {
        $countPriority = App::call()->ordersRepository->getOrdersMyPriority();
        echo $countPriority['total'];
    }

    public function actionGetMaxScriptNum()
    {
        $fr_script_id = App::call()->request->getParams()['fr_script_id'];
        $result = App::call()->ordersRepository->maxScriptNum($fr_script_id);
        echo json_encode($result);
    }

    public function actionGetOneProject()
    {
        $proj_id = App::call()->request->getParams()['proj_id'];
        $result = App::call()->ordersRepository->getOne($proj_id);
        echo json_encode(['result' => $result]);
    }

    public function actionStatusPick($proj_id)
    {
        $order = App::call()->ordersRepository->getOne($proj_id);
        if ($order->status_manager === '1' && $order->status_client === '26')
            return 'Отправлено менеджерам\Информация от менеджеров не требуется';
        if ($order->status_manager !== '1' && $order->status_project === '6' && $order->status_client === '26')
            return 'Требуется инвентори\Информация от проектного отдела не требуется';
        if ($order->status_manager !== '1' && $order->status_project !== '6' && $order->status_lpo === '11' && $order->status_client === '26')
            return 'Требуется информация от ЛТО/Информация от ЛТО не требуется';
        if (($order->status_manager === '3' || $order->status_manager === '47') && $order->status_project === '34' && $order->status_client === '26')
            return 'Выполнение запроса невозможно (количество квот)';
        if ($order->status_manager !== '1' && $order->status_project === '9' && $order->status_client === '26')
            return 'Информации достаточно/можно готовить квоту';
        if ($order->status_manager !== '1' && $order->status_lpo !== '11' && $order->status_boss !== '21' && $order->status_client === '26')
            return 'Согласование с ведущим координатором BD';
        if ($order->status_manager !== '1' && $order->status_lpo !== '11' && $order->status_boss === '41' && $order->status_client === '26')
            return 'Согласование Reminder с руководством/Срочное согласование Reminder с руководством';
        if ($order->status_manager !== '1' && $order->status_client === '27')
            return 'Получить PO/ ответ от заказчика/ Communications';
        return '';
    }

    public function actionGetAll()
    {
        $orders = App::call()->ordersRepository->getAllLastReportClient();
        $staff = App::call()->ordersRepository->getOrdersStaff();
        $company = App::call()->companyRepository->getAll();
        $status = App::call()->ordersStatusRepository->getAll();
        echo json_encode([
            'orders' => $orders,
            'staff' => $staff,
            'company' => $company,
            'status' => $status
        ]);
    }

    public function actionGetbyDate()
    {

        $dateOne = App::call()->request->getParams()['dateOne'];
        $dateTwo = App::call()->request->getParams()['dateTwo'];
        $orders = App::call()->ordersRepository->getAllLastReportClientbyDate($dateOne, $dateTwo);
        $staff = App::call()->ordersRepository->getOrdersStaff();
        $company = App::call()->companyRepository->getAll();
        $status = App::call()->ordersStatusRepository->getAll();
        echo json_encode([
            'orders' => $orders,
            'staff' => $staff,
            'company' => $company,
            'status' => $status
        ]);
    }

    public function actionGetbyDateDeadLine()
    {

        $dateOne = App::call()->request->getParams()['dateOne'];
        $dateTwo = App::call()->request->getParams()['dateTwo'];
        $orders = App::call()->ordersRepository->getOrdersbyDateDeadLine($dateOne, $dateTwo);
        $staff = App::call()->ordersRepository->getOrdersStaff();
        $company = App::call()->companyRepository->getAll();
        $status = App::call()->ordersStatusRepository->getAll();
        echo json_encode([
            'orders' => $orders,
            'staff' => $staff,
            'company' => $company,
            'status' => $status
        ]);
    }




    public function actionReportForClinet()
    {
        $this->layout = 'admin';
        echo $this->render('orders/reportForClinet', [

        ]);

    }


    public function actionOrdersDeadline()
    {
        $this->layout = 'admin';
        echo $this->render('orders/ordersDeadline', [
        ]);

    }

    public function actionCopy()
    {
        $proj_old_id = App::call()->request->getParams()['id'];
        $proj_old = App::call()->ordersRepository->getOne($proj_old_id);
        $proj_cpy = new Orders();
        foreach ($proj_old as $fieldName => $value)
            if ($fieldName !== 'proj_id')
                $proj_cpy->{$fieldName} = $value;
        $new_proj_id = App::call()->ordersRepository->save($proj_cpy);

        $old_diseases = App::call()->orderDiseasesRepository->getWhere(['order_id' => $proj_old_id]);
        foreach ($old_diseases as $old_disease) {
            $order_disease_cpy = new OrderDiseases();
            foreach ($old_disease as $diseaseField => $diseaseVal) {
                if ($diseaseField === 'order_id')
                    $order_disease_cpy->{$diseaseField} = $new_proj_id;
                else if ($diseaseField !== 'id')
                    $order_disease_cpy->{$diseaseField} = $diseaseVal;
            }
            App::call()->orderDiseasesRepository->save($order_disease_cpy);
        }

        $old_samples = App::call()->diseasesBiospecimenTypesRepository->getWhere(['order_id' => $proj_old_id]);
        foreach ($old_samples as $old_sample) {
            $order_sample_cpy = new DiseasesBiospecimenTypes();
            foreach ($old_sample as $sampleField => $sampleVal) {
                if ($sampleField === 'order_id')
                    $order_sample_cpy->{$sampleField} = $new_proj_id;
                else if ($sampleField !== 'id')
                    $order_sample_cpy->{$sampleField} = $sampleVal;
            }
            App::call()->diseasesBiospecimenTypesRepository->save($order_sample_cpy);
        }

        $old_quotes = App::call()->quoteRepository->getWhere(['proj_id' => $proj_old_id]);
        foreach ($old_quotes as $quote) {
            $quote_cpy = new Quote();
            foreach ($quote as $quoteField => $quoteVal) {
                if ($quoteField === 'proj_id')
                    $quote_cpy->{$quoteField} = $new_proj_id;
                else if ($quoteField !== 'id')
                    $quote_cpy->{$quoteField} = $quoteVal;
            }
            $new_quote_id = App::call()->quoteRepository->save($quote_cpy);
            $quote_doctors = App::call()->quoteDoctorRepository->getWhere(['quote_id' => $quote['id']]);
            foreach ($quote_doctors as $quote_doctor) {
                $quote_doc_cpy = new QuoteDoctor();
                foreach ($quote_doctor as $quoteDocField => $quoteDocVal) {
                    if ($quoteDocField === 'quote_id')
                        $quote_doc_cpy->{$quoteDocField} = $new_quote_id;
                    else if ($quoteDocField !== 'id')
                        $quote_doc_cpy->{$quoteDocField} = $quoteDocVal;
                }
                App::call()->quoteDoctorRepository->save($quote_doc_cpy);
            }
            $disease_biospecimen_types = App::call()->quoteSampleRepository->getWhere(['quote_id' => $quote['id']]);
            foreach ($disease_biospecimen_types as $quote_sample) {
                $quote_sample_cpy = new QuoteSample();
                foreach ($quote_sample as $quoteSampleField => $quoteSampleVal) {
                    if ($quoteSampleField === 'quote_id')
                        $quote_sample_cpy->{$quoteSampleField} = $new_quote_id;
                    else if ($quoteSampleField !== 'id')
                        $quote_sample_cpy->{$quoteSampleField} = $quoteSampleVal;
                }
                App::call()->quoteSampleRepository->save($quote_sample_cpy);
            }
        }
    }

    public function actionGetForCount()
    {
        $orders = App::call()->ordersRepository->getAll();
        $company = App::call()->companyRepository->getAll();
        echo json_encode([
            'orders' => $orders,
            'company' => $company,
        ]);
    }

    public function actionOrdersCount()
    {
        $this->layout = 'admin';
        echo $this->render('orders/orders_cnt', []);
    }

    public function actionCountByDiseases()
    {
        $category_id = App::call()->request->getParams()['category_id'];
        $group_id = App::call()->request->getParams()['group_id'];
        $disease_id = App::call()->request->getParams()['disease_id'];
        $orders_diseases = [];
        if ($disease_id !== '0') {
            $orders_diseases[] = App::call()->orderDiseasesRepository->getWhere(['disease_id' => $disease_id]);
        } else if ($group_id !== '0') {
            $group_diseases = App::call()->diseaseRepository->getWhere(['group_id' => $group_id]);
            foreach ($group_diseases as $disease) {
                $orders_diseases = array_merge($orders_diseases, App::call()->orderDiseasesRepository->getWhere(['disease_id' => $disease['id']]));
            }
        } else if ($category_id !== '0') {
            $category_groups = App::call()->diseaseGroupRepository->getWhere(['category_id' => $category_id]);
            foreach ($category_groups as $group) {
                $group_diseases = App::call()->diseaseRepository->getWhere(['group_id' => $group['id']]);
                foreach ($group_diseases as $disease) {
                    $orders_diseases = array_merge($orders_diseases, App::call()->orderDiseasesRepository->getWhere(['disease_id' => $disease['id']]));
                }
            }
        }
        echo json_encode([
            'result' => $orders_diseases
        ]);
    }







}
