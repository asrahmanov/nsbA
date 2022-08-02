<?php

namespace app\controllers;

use app\engine\App;
use app\models\entities\Files;
use app\models\entities\LogChange;
use app\models\entities\Mail;
use app\models\entities\NewTickets;
use app\models\entities\Offer;
use app\models\entities\OfferRu;
use app\models\entities\OfferApe;
use app\models\entities\OfferApproval;
use app\models\entities\Orders;
use app\models\entities\OrdersStatusActions;
use app\models\entities\Po;
use app\models\entities\Priority;
use app\models\entities\WorksheetsInvertory;
use app\models\entities\WorksheetsLaboratory;


class PoController extends Controller
{
    protected $layout = 'stylemain';
    protected $defaultAction = 'po';
    protected $render = 'po/po';


    public function actionPo()
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


        $status_po = App::call()->postatusRepository->getAll();


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
            'user_id' => $myId,
            'status_po' => $status_po
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
                $fr_table = App::call()->poRepository->getAllbyDateDesc($dateTwo, $dateOne);
            } else {
                $fr_table = App::call()->poRepository->getOrderByDesc($dateTwo, $dateOne);
            }
        } else {
            if ($dateOne != '' and $dateTwo != '') {
                $fr_table = App::call()->poRepository->getAllbyDateMin($dateTwo, $dateOne);
            } else {
                $fr_table = App::call()->poRepository->getAll();
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
            $json['data'][$i]['po_number'] = $fr_table[$i]['po_number'];
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

           $po_statusAll = App::call()->postatusRepository->getAll();
            $po_statusArray = [];

            for ($k = 0; $k < count($po_statusAll); $k++) {
                $po_statusArray[$po_statusAll[$k]['status_id']] = $po_statusAll[$k]['status_name'];
            }

           $json['data'][$i]['po_status'] = $po_statusArray[$fr_table[$i]['po_status']];
           // $json['data'][$i]['po_status'] = count($po_statusAll);


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

        $fr_table = App::call()->poRepository->getManagerAll();

        for ($i = 0; $i < count($fr_table); $i++) {
            $json['data'][$i]['po_number'] = $fr_table[$i]['po_number'];
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

        $fr_table = App::call()->poRepository->GetOrdersMy();
        for ($i = 0; $i < count($fr_table); $i++) {
            $json['data'][$i]['po_number'] = $fr_table[$i]['po_number'];
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
        $fr = App::call()->poRepository->GetOrdersOne($idFR);

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

        $order_diseases = App::call()->poDiseaseRepository->getWhere(['proj_id' => $idFR]);
        foreach ($order_diseases as $key => $order_disease) {
            $disease = $diseasesById->{$order_disease['disease_id']};
            $order_diseases[$key]['disease'] = $disease['disease_name'];
            $order_diseases[$key]['mutation'] = $disease['disease_name_russian_old'];
            $order_diseases[$key]['disease_name_russian'] = $disease['disease_name_russian'];
        }
        $diseases_biospecimen_types = App::call()->poDiseaseSampleModRepository->getWhere(['proj_id' => $idFR]);
        foreach ($diseases_biospecimen_types as $key => $diseases_biospecimen_type) {
            $biospecimen_type = $biospecimenTypesById->{$diseases_biospecimen_type['sample_id']};
            $diseases_biospecimen_types[$key]['biospecimen_type'] = $biospecimen_type['biospecimen_type'];
            $modification = $sampleModsById->{$diseases_biospecimen_type['mod_id']};
            $diseases_biospecimen_types[$key]['modification'] = $modification['modification'];
        }

        $diseases_biospecimen_types_rus = App::call()->poDiseaseSampleModRepository->getWhere(['proj_id' => $idFR]);
        foreach ($diseases_biospecimen_types as $key => $diseases_biospecimen_type) {
            $biospecimen_type = $biospecimenTypesById->{$diseases_biospecimen_type['sample_id']};
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
                $quotesByDiseaseAndSamples = App::call()->quoteRepository->getHistoryQuotesByDiseaseAndSampleIds($order_disease['disease_id'], $disease_biospecimen_type['sample_id']);
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
                $biospecimen_type = App::call()->biospecimenTypeRepository->getOne($disease_biospecimen_type['sample_id']);
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
            $offer->proj_id = $idFR;
            $offer->date_offer = DATE('Y-m-d');
            $offer->date_valid = DATE('Y-m-d');
            $offer->courier_id = 1;
            App::call()->offerRepository->save($offer);
        }
        // Конец создания коммерческого предложения

        // Проверка если у нас не создано Русское коммерческое предложение его нужно создать -- НАЧАЛО
        $checkOfferRu = App::call()->offerRuRepository->getWhere(['proj_id' => $idFR]);

        $idRu = $checkOfferRu[0]['id'];
        $offer_ru = App::call()->offerRuRepository->getObject($idRu);
        if (!$offer_ru) {
            $offer_ru = new OfferRu();
            $offer_ru->proj_id = $idFR;
            $offer_ru->date_offer = DATE('Y-m-d');
            $offer_ru->date_valid = DATE('Y-m-d');
            $offer_ru->courier_id = 1;
            App::call()->offerRuRepository->save($offer_ru);
        }
        // Конец создания Русского коммерческого предложения

        // Проверка если у нас не создано коммерческое предложение макак его нужно создать -- НАЧАЛО
        $checkApeOffer = App::call()->offerApeRepository->getWHERE(['proj_id' => $idFR]);
        $idApe = $checkApeOffer[0]['id'];
        $offerApe = App::call()->offerApeRepository->getObject($idApe);
        if (!$offerApe) {
            $offerApe = new OfferApe();
            $offerApe->proj_id = $idFR;
            $offerApe->date_offer = DATE('Y-m-d');
            $offerApe->date_valid = DATE('Y-m-d');
            $offerApe->courier_id = 1;
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
            
        //$time = microtime(true) - $start;



        $st = $fr['status_manager'];

        $department_id = App::call()->session->getSession('department_id');

//        if ($department_id == 4) {
//
//        } else if ($role == 3 and $st != 2 and $st != 3) {
//            echo $this->renderTemplate('404', $params = [
//                'error' => 'Статус заявки не позволяет ее просмотр'
//            ]);
//            die();
//        }

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

        $po_status = App::call()->postatusRepository->getAll();

        $po_data_arr = App::call()->poDataRepository->getWhere(['po_id' => $fr['id']]);
//        var_dump($fr['id']);
        $po_data = [];
        if (count($po_data_arr) > 0) {
            $po_data = $po_data_arr[0];
        }

        echo $this->render('po/poInfo', [
            'fr' => $fr,
            'po_data' => $po_data,
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
            'biospecimen_types' => $biospecimen_types,
            'po_status' => $po_status
        ]);
    }

    public function actionSave()
    {

        if (isset(App::call()->request->getParams()['proj_id'])) {
            $proj_id = App::call()->request->getParams()['proj_id'];
            $po = App::call()->poRepository->GetOrdersOne($proj_id);
            $id = $po['id'];
            $order = App::call()->poRepository->getObject($id);
            $orderOld = App::call()->poRepository->getObject($id);

        } else {
            $order = new Po();
            $orderOld = new Po();
            $order->po_status = 1;
        }

        if (isset(App::call()->request->getParams()['fr_date'])) {
            $fr_date = App::call()->request->getParams()['fr_date'];
            $order->fr_date = $fr_date;
        }

        if (isset(App::call()->request->getParams()['po_text'])) {
            $po_text = App::call()->request->getParams()['po_text'];
            $order->po_text = $po_text;
        }

        if (isset(App::call()->request->getParams()['po_status'])) {
            $po_status = App::call()->request->getParams()['po_status'];
            $order->po_status = $po_status;
        }

        if (isset(App::call()->request->getParams()['lab'])) {
            $lab = App::call()->request->getParams()['lab'];
            $order->lab = $lab;
        }

        if (isset(App::call()->request->getParams()['po_number'])) {
            $po_number = App::call()->request->getParams()['po_number'];
            $order->po_number = $po_number;
        }

        if (isset(App::call()->request->getParams()['proj_id'])) {
            $proj_id = App::call()->request->getParams()['proj_id'];
            $order->proj_id = $proj_id;
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
            $order->status_manager = $status_manager;
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
            $order->unformal_quote = $unformal_quote;
        }

        if (isset(App::call()->request->getParams()['quote_date'])) {
            $quote_date = App::call()->request->getParams()['quote_date'];
            $order->quote_date = $quote_date;
        }

        if (isset(App::call()->request->getParams()['quotes_from_managers'])) {
            $quotes_from_managers = App::call()->request->getParams()['quotes_from_managers'];
            $order->quotes_from_managers = $quotes_from_managers;
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

        if (isset(App::call()->request->getParams()['status_client'])) {
            $status_client = App::call()->request->getParams()['status_client'];
            $order->status_client = $status_client;
        }

        if (isset(App::call()->request->getParams()['margin'])) {
            $margin = App::call()->request->getParams()['margin'];
            $order->margin = $margin;
        }
        if (isset(App::call()->request->getParams()['quote_type'])) {
            $quote_type = App::call()->request->getParams()['quote_type'];
            $order->quote_type = $quote_type;
        }


        if (isset(App::call()->request->getParams()['deadline'])) {
            $deadline = App::call()->request->getParams()['deadline'];
            $order->deadline = $deadline;
        }

        if (isset(App::call()->request->getParams()['history_po'])) {
            $history_po = App::call()->request->getParams()['history_po'];
            $order->history_po = $history_po;
        }

        if (isset(App::call()->request->getParams()['historical_data'])) {
            $historical_data = App::call()->request->getParams()['historical_data'];
            if ($historical_data != '') {
                $order->historical_data = $historical_data;
            }

        }

        $result = App::call()->poRepository->save($order);
        echo $result;

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
        $order = App::call()->poRepository->getObject($proj_id);
        $order->priority_id = $priority;
        $result = App::call()->poRepository->save($order);
        echo $result;
    }

    public function actionCountMyPriority()
    {
        $countPriority = App::call()->poRepository->getOrdersMyPriority();
        echo $countPriority['total'];
    }

    public function actionGetMaxScriptNum()
    {
        $fr_script_id = App::call()->request->getParams()['fr_script_id'];
        $result = App::call()->poRepository->maxScriptNum($fr_script_id);
        echo json_encode($result);
    }

    public function actionGetOneProject()
    {
        $proj_id = App::call()->request->getParams()['proj_id'];
        $result = App::call()->poRepository->getOne($proj_id);
        echo json_encode(['result' => $result]);
    }

    public function actionStatusPick($proj_id)
    {
        $order = App::call()->poRepository->getOne($proj_id);
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
        $orders = App::call()->poRepository->getAllLastReportClient();
        $staff = App::call()->poRepository->getOrdersStaff();
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
        $orders = App::call()->poRepository->getAllLastReportClientbyDate($dateOne, $dateTwo);
        $staff = App::call()->poRepository->getOrdersStaff();
        $company = App::call()->companyRepository->getAll();
        $status = App::call()->ordersStatusRepository->getAll();
        echo json_encode([
            'orders' => $orders,
            'staff' => $staff,
            'company' => $company,
            'status' => $status
        ]);
    }

    public function actionPomanager()
    {
        $user_id= App::call()->session->getSession('user_id');
        $poqoute = App::call()->poquoteRepository->getAll();

        $po = App::call()->poworksheetsRepository->getJoinPo();



        $this->layout = 'admin';
        echo $this->render('po/pomanager', [
        'po' => $po,
        'poqoute' => $poqoute,
        'user_id' => $user_id,
        ]);

    }
    public function actionPoIsset()
    {
        $proj_id = App::call()->request->getParams()['proj_id'];
        $po = App::call()->poRepository->GetOrdersOne($proj_id);
        echo json_encode(['result' => $po]);


    }
}
