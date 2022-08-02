<?php

namespace app\controllers;

use app\engine\App;
use app\models\entities\Chat;
use app\models\entities\DiseasesBiospecimenTypes;
use app\models\entities\LogChange;
use app\models\entities\Mail;
use app\models\entities\NewTickets;
use app\models\entities\OrderDiseases;
use app\models\entities\Orders;
use app\models\entities\OrdersStatusActions;
use app\models\entities\Po;
use app\models\entities\PoDisease;
use app\models\entities\PoDiseaseSampleMod;
use app\models\entities\WorksheetsInvertory;
use app\models\entities\WorksheetsLaboratory;


class ApiController extends Controller
{

    private $ip;
    private $access = false;
    private $token;


    protected $layout = 'api';
    protected $defaultAction = 'info';
    protected $folder = 'api/info';

    public function actionIndex()
    {
        echo $this->render($this->folder);

    }

    public function actionManager()
    {
        $this->defaultAction = 'manager';
        echo $this->render("{$this->layout}/{$this->defaultAction}");
    }

    public function actionSite()
    {
        $this->defaultAction = 'site';
        echo $this->render("{$this->layout}/{$this->defaultAction}");
    }

    public function actionDisease()
    {
        $this->defaultAction = 'disease';
        echo $this->render("{$this->layout}/{$this->defaultAction}");
    }

    public function actionScripts()
    {
        $this->defaultAction = 'scripts';
        echo $this->render("{$this->layout}/{$this->defaultAction}");
    }

    public function actionCurrency()
    {
        $this->defaultAction = 'currency';
        echo $this->render("{$this->layout}/{$this->defaultAction}");
    }

    public function actionOrder()
    {
        $this->defaultAction = 'order';
        echo $this->render("{$this->layout}/{$this->defaultAction}");
    }


    public function checkAccess()
    {
        $client = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote = @$_SERVER['REMOTE_ADDR'];

        if (filter_var($client, FILTER_VALIDATE_IP)) $this->ip = $client;
        elseif (filter_var($forward, FILTER_VALIDATE_IP)) $this->ip = $forward;
        else $this->ip = $remote;

        $this->token = App::call()->request->getParams()['token'];


        $whiteList = App::call()->ipAccessRepository->getAllDeleted();
        foreach ($whiteList as $key => $value) {
            if ($whiteList[$key]['ip'] == $this->ip) {
                if ($whiteList[$key]['token'] == $this->token) {
                    $this->access = true;
                }
            };
        }

        if (!$this->access) {
            die('access denied');
        }

    }

    public function actionInfo()
    {
        ////$this->checkAccess();
        echo $this->render($this->folder, [
            'token' => $this->token
        ]);
    }


    // Получение всех Менеджеров + Старшие менеджеры
    public function actionGetManagers()
    {
        //$this->checkAccess();
        $managers = App::call()->usersRepository->getManagers();
        echo json_encode(['result' => $managers]);
    }

    // Все сайты
    public function actionGetSites()
    {
        //$this->checkAccess();
        $sites = App::call()->sitesRepository->getAll();
        echo json_encode(['result' => $sites]);
    }

    // Сайты одного юзера
    public function actionGetUserJoinSitesByUserId()
    {
        //$this->checkAccess();
        $user_id = App::call()->request->getParams()['user_id'];
        $result = App::call()->usersRepository->getUserJoinSitesByUserId($user_id);
        echo json_encode(['result' => $result]);
    }

    // Все пользователи всех сайтов
    public function actionGetUserALLJoinSitesALL()
    {
        //$this->checkAccess();
        $result = App::call()->usersRepository->getUserALLJoinSitesALL();
        echo json_encode(['result' => $result]);
    }

    // Все пользователи всех сайтов
    public function actionTicketCheck()
    {
        ////$this->checkAccess();
        // Найти все косячные
        $err_score = App::call()->ticketsScoreRepository->finderror();

        // Обновляем все косячные на 10
        for ($i = 0; $i < count($err_score); $i++) {
            $ticketScore = App::call()->ticketsScoreRepository->getObject($err_score[$i]['id']);
            $ticketScore->score = 10;
            App::call()->ticketsScoreRepository->save($ticketScore);

            $ticket = App::call()->newTicketsRepository->getObject($err_score[$i]['ticket_id']);
            $sumScore = App::call()->ticketsScoreRepository->getScore($err_score[$i]['ticket_id']);


            if ($ticket->closed_status == 'ok') {
                $ticket->score = $sumScore[0]['score'] + 10;
            }

            if ($ticket->closed_status == 'late') {
                $ticket->score = $sumScore[0]['score'] + 5;
            }

            if ($ticket->closed_status == 'fail') {
                $ticket->score = $sumScore[0]['score'];
            }


            App::call()->newTicketsRepository->save($ticket);

            var_dump($ticket);
        }
    }


    // Методы для получение скриптов ( клиентов )

    public function actionGetScripts()
    {
        //$this->checkAccess();
        $scripts = App::call()->companyRepository->getAllApi();
        echo json_encode(['result' => $scripts]);
    }

    // Создание клиента и его редактирования
    public function actionModifyAndCreateScript()
    {

        //$this->checkAccess();
        if (isset(App::call()->request->getParams()['script_id'])) {
            $script_id = App::call()->request->getParams()['script_id'];
            $company = App::call()->companyRepository->getObject($script_id);

        } else {
            $company = new Company();
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

        $result = App::call()->companyRepository->save($company);
        echo $result;
    }

    // Получение списка волют

    public function actionGetCurrency()
    {
        //$this->checkAccess();
        $currency = App::call()->currencyRepository->getAll();
        echo json_encode(['result' => $currency]);
    }

    // Получения списка Категорий

    public function actionGetDiseaseCategory()
    {
        //$this->checkAccess();
        $diseaseCategory = App::call()->diseaseCategoryRepository->getAll();
        echo json_encode(['result' => $diseaseCategory]);
    }

    // Получения списка Групп

    public function actionGetDiseaseGroup()
    {
        //$this->checkAccess();
        $diseaseGroup = App::call()->diseaseGroupRepository->getAll();
        echo json_encode(['result' => $diseaseGroup]);
    }

    // Получения списка Болезней

    public function actionGetDisease()
    {
        //$this->checkAccess();
        $disease = App::call()->diseaseRepository->getAll();
        echo json_encode(['result' => $disease]);
    }


    // Получения заявкок по script_id

    public function actionGetOrderByScriptId()
    {
        //$this->checkAccess();
        if (isset(App::call()->request->getParams()['script_id'])) {
            $script_id = App::call()->request->getParams()['script_id'];
            $orders = App::call()->ordersRepository->getByScriptId($script_id);
            $staff = App::call()->ordersRepository->getOrdersStaff();
        } else {
            $orders = [];
            $staff = [];
        }

        $OrdersRender = [];
        for ($i = 0; $i < count($orders); $i++) {

            $OrdersRender[$i]['proj_id'] = $orders[$i]['proj_id'];
            $OrdersRender[$i]['fr_date'] = $orders[$i]['fr_date'];
            $OrdersRender[$i]['internal_id'] = $orders[$i]['internal_id'];
            $OrdersRender[$i]['external_id'] = $orders[$i]['external_id'];
            $OrdersRender[$i]['project_name'] = $orders[$i]['project_name'];
            $OrdersRender[$i]['comments'] = $orders[$i]['comments'];
            $OrdersRender[$i]['file_qoute_send'] = $orders[$i]['file_qoute_send'];
            $OrdersRender[$i]['project_name'] = $orders[$i]['project_name'];
            $OrdersRender[$i]['project_details'] = $orders[$i]['project_details'];

            $OrdersRender[$i]['client_status'] = $this->getClientStatus(
                $orders[$i]['status_manager'],
                $orders[$i]['status_project'],
                $orders[$i]['status_client'],
                $orders[$i]['status_boss']
            );

            $key = array_search($orders[$i]['proj_id'], $staff);
            if($key == false) {
                $OrdersRender[$i]['staf'] = 'The value is undefined';
            } else {
                $OrdersRender[$i]['staf'] = $staff[$key]['name'];

            }




        }
        echo json_encode(['result' => $OrdersRender]);

    }


    // Полечение заявки по proj_id
    public function actionGetOrderByOrderId()
    {
        if (isset(App::call()->request->getParams()['proj_id']) and isset(App::call()->request->getParams()['script_id'])) {
            $proj_id = App::call()->request->getParams()['proj_id'];
            $script_id = App::call()->request->getParams()['script_id'];
            $orders = App::call()->ordersRepository->getByScriptByProj_id($proj_id,$script_id);

        } else {
            $orders = [];
        }

        $OrdersRender = [];
        for ($i = 0; $i < count($orders); $i++) {

            $OrdersRender[$i]['proj_id'] = $orders[$i]['proj_id'];
            $OrdersRender[$i]['fr_date'] = $orders[$i]['fr_date'];
            $OrdersRender[$i]['internal_id'] = $orders[$i]['internal_id'];
            $OrdersRender[$i]['external_id'] = $orders[$i]['external_id'];
            $OrdersRender[$i]['project_name'] = $orders[$i]['project_name'];
            $OrdersRender[$i]['comments'] = $orders[$i]['comments'];
            $OrdersRender[$i]['file_qoute_send'] = $orders[$i]['file_qoute_send'];
            $OrdersRender[$i]['script_id'] = $orders[$i]['fr_script_id'];
            $OrdersRender[$i]['project_name'] = $orders[$i]['project_name'];
            $OrdersRender[$i]['project_details'] = $orders[$i]['project_details'];

            $OrdersRender[$i]['client_status'] = $this->getClientStatus(
                $orders[$i]['status_manager'],
                $orders[$i]['status_project'],
                $orders[$i]['status_client'],
                $orders[$i]['status_boss']
            );


        }

        echo json_encode(['result' => $OrdersRender]);
    }


    // Анализ заявок получения статуса клиента
    public function getClientStatus($status_manager, $status_project, $status_client, $status_boss)
    {


        if (
            ($status_manager == 2 or $status_manager == 3 or $status_manager == 5)
            and ($status_project == 7 or $status_project == 10)
            and ($status_client != 28 and $status_client != 29 and $status_client != 33 and $status_client != 49 and $status_client != 38 and $status_client != 39)
        ) {
            return (
            [
                "ru" => 'Собирается информация о доступности образцов и скорости набора',
                "en" => 'Feasibility/collection rate evaluation in progress'

            ]);
        }

        //2
        if (
            ($status_manager == 2 or $status_manager == 3 or $status_manager == 5)
            and ($status_project == 7 or $status_project == 10 or $status_project == 11)
            and ($status_client == 49)
        ) {
            return (
            [
                "ru" => 'Изучается возможность обновления квоты в соответствии с новыми требованиями заказчика',
                "en" => 'New or amended requirments are received. Feasibility evaluation in progress'

            ]);
        }

        // 3
        if (
            ($status_manager != 1 and $status_project == 9)
            and ($status_client != 49 and $status_client != 36 and $status_client != 3 and $status_client != 27)
        ) {
            return (
            [
                "ru" => 'Требуемые образцы доступны,  готовится квота',
                "en" => 'The required samples are available. Proposal  in progress'

            ]);
        }

        // 3
        if (($status_manager == 4 or $status_project != 6)
            and ($status_client != 49 and $status_client != 36 and $status_client != 38 and $status_client != 27)
        ) {
            return (
            [
                "ru" => 'Требуемые образцы доступны,  готовится квота',
                "en" => 'The required samples are available. Proposal  in progress'

            ]);
        }


        // 4
        if (($status_client == 38)) {
            return (
            [
                "ru" => 'Есть вопросы к заказчику',
                "en" => 'Questions sent to the Customer'

            ]);
        }

        // 5
        if (
            ($status_manager != 1)
            and ($status_project != 6)
            and ($status_boss != 21)
            and ($status_client == 27)
        ) {
            return (
            [
                "ru" => 'Квота отослана заказчику, ожидается обратная связь',
                "en" => 'The quote is provided. A feedback is much appreciated'

            ]);
        }

        // 6
        if (($status_manager != 1)
            and ($status_project != 6)
            and ($status_boss == 44)

        ) {
            return (
            [
                "ru" => 'Квота проходит внутреннее одобрение',
                "en" => 'The quote is under internal approval'

            ]);
        }
        // 7
        if (($status_manager != 1)
            and ($status_project == 7)
            and ($status_client == 51)
        ) {
            return (
            [
                "ru" => 'Готовится инвентори',
                "en" => 'The inventory will be provided soon'

            ]);
        }

        // 8
        if (($status_manager != 1)
            and ($status_project == 8)
            and ($status_boss == 21)
            and ($status_client == 30)
        ) {
            return (
            [
                "ru" => 'Инвентори предоставлен. Запросить обратную связь по инвентори',
                "en" => 'The inventory was provided. A feedback is much appreciated'

            ]);
        }

        return (
        [
            "ru" => '-',
            "en" => `-`

        ]);

    }

    // Получения списка сотрудникво по script_id
    public function actionGetCompanyStafByScriptId()
    {
        //$this->checkAccess();
        if (isset(App::call()->request->getParams()['script_id'])) {
            $script_id = App::call()->request->getParams()['script_id'];
            $staff = App::call()->companyStaffRepository->getByCompany($script_id);
        } else {
            $staff = [];
        }
        echo json_encode(['result' => $staff]);
    }


    public function actionOrderCreate()
    {
        //$this->checkAccess();
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
                    $order->communication_comment = 'created at nbs platform auto';
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
                $mailSend->email = 'ops@nbioservice.com';
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
                $mailSend->email = 'ops@nbioservice.com';
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
                $mailSend->email = 'oleg.granstrem@nbioservice.com';
                $mailSend->subject = "Reminder № {$order->internal_id}";
                $mailSend->text_mail = "Добрый день," . PHP_EOL . "Необходимо предоставить ответ на запрос № 
                    <a href='http://crm.i-bios.com/newTickets/view/?id=$tick_id'>{$order->internal_id}  ($order->project_name)</a>";
                $mailSend->send_time = $mailSendTime;
                $mailSend->send = 'NO';
                App::call()->mailRepository->save($mailSend);
                $mailSend2 = new Mail();
                $mailSend2->email = 'ops@nbioservice.com';
                $mailSend2->subject = "Reminder № {$order->internal_id}";
                $mailSend2->text_mail = "Добрый день," . PHP_EOL . "Необходимо предоставить ответ на запрос № 
                    <a href='http://crm.i-bios.com/newTickets/view/?id=$tick_id'>{$order->internal_id} ($order->project_name)</a>";
                $mailSend2->send_time = $mailSendTime;
                $mailSend2->send = 'NO';
                App::call()->mailRepository->save($mailSend2);
                $mailSend3 = new Mail();
                $mailSend3->email = 'coms@nbioservice.com';
                $mailSend3->subject = "Reminder № {$order->internal_id}";
                $mailSend3->text_mail = "Добрый день," . PHP_EOL . "Необходимо предоставить ответ на запрос № 
                    <a href='http://crm.i-bios.com/newTickets/view/?id=$tick_id'>{$order->internal_id} ($order->project_name)</a>";
                $mailSend3->send_time = $mailSendTime;
                $mailSend3->send = 'NO';
                App::call()->mailRepository->save($mailSend3);
            }
        }

        if (isset(App::call()->request->getParams()['margin'])) {
            $margin = App::call()->request->getParams()['margin'];
            $order->margin = $margin;
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
            // Требуется информация от ЛТО
            if (App::call()->request->getParams()['status_lpo'] == 12) {
                // Создаем задачу для отдела лабаратории

                if (isset(App::call()->request->getParams()['proj_id'])) {
                    $proj_id = App::call()->request->getParams()['proj_id'];
                } else {
                    $proj_id = $result;
                }

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

        // Блок для проверки создавать ли  задачу менеджеру
        $prog_log = '';
        if (isset(App::call()->request->getParams()['proj_id'])) {
            $prog_log = App::call()->request->getParams()['proj_id'];
            if ($order->status_manager == 2) {
                App::call()->worksheetsRepository->generateOnebyOneOrders($prog_log);
            } elseif ($order->status_manager == 46) {
                // Приматы
                App::call()->worksheetsRepository->generateOnebyOneOrdersPrimat($prog_log);
            }
        } else {
            if ($result > 0) {
                $prog_log = $result;
                if ($order->status_manager == 2) {
                    App::call()->worksheetsRepository->generateOnebyOneOrders($result);
                    // Приматы
                } elseif ($order->status_manager == 46) {
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


    public function actionGetSample()
    {
        //$this->checkAccess();
        $array = App::call()->biospecimenTypeRepository->getAll();
        echo json_encode(['result' => $array]);
    }


    public function actionGetDiseaseByOrder()
    {
        //$this->checkAccess();
        if (isset(App::call()->request->getParams()['proj_id'])) {
            $proj_id = App::call()->request->getParams()['proj_id'];
            $disease = App::call()->orderDiseasesRepository->GetDiseaseByOrder($proj_id);
            echo json_encode(['result' => $disease]);
        }

    }


    public function actionGetALlBiospecimenTypesByOrder()
    {
        //$this->checkAccess();
        if (isset(App::call()->request->getParams()['proj_id'])) {
            $proj_id = App::call()->request->getParams()['proj_id'];
            $all = App::call()->diseasesBiospecimenTypesRepository->GetdiseasesBiospecimenTypesRepository($proj_id);
            echo json_encode(['result' => $all]);
        }

    }

    public function actionGetALlBiospecimenTypesByOrderLeft()
    {
        //$this->checkAccess();
        if (isset(App::call()->request->getParams()['proj_id'])) {
            $proj_id = App::call()->request->getParams()['proj_id'];
            $all = App::call()->diseasesBiospecimenTypesRepository->actionGetALlBiospecimenTypesByOrderLeft($proj_id);
            echo json_encode(['result' => $all]);
        }

    }


    // Получения списка модификаций
    public function actionGetMod()
    {

        $all = App::call()->sampleModRepository->getAll();
        echo json_encode(['result' => $all]);

    }


    public function actionAddDiseaseByOrder()
    {
        //$this->checkAccess();
        if (isset(App::call()->request->getParams()['id'])) {
            $id = App::call()->request->getParams()['id'];
            $item = App::call()->orderDiseasesRepository->getObject($id);
        } else {
            $item = new OrderDiseases();
        }
        if (isset(App::call()->request->getParams()['order_id'])) {
            $order_id = App::call()->request->getParams()['order_id'];
            $item->order_id = $order_id;
        }
        if (isset(App::call()->request->getParams()['disease_id'])) {
            $disease_id = App::call()->request->getParams()['disease_id'];
            $item->disease_id = $disease_id;
        }
        $result = App::call()->orderDiseasesRepository->save($item);
        echo $result;
    }


    public function actionAddDiseaseAndSample()
    {
        //$this->checkAccess();

        if (isset(App::call()->request->getParams()['id'])) {
            $id = App::call()->request->getParams()['id'];
            $item = App::call()->diseasesBiospecimenTypesRepository->getObject($id);
        } else {
            $item = new DiseasesBiospecimenTypes();
        }


        if (isset(App::call()->request->getParams()['order_id'])) {
            $order_id = App::call()->request->getParams()['order_id'];
            $item->order_id = $order_id;
        }
        if (isset(App::call()->request->getParams()['disease_id'])) {
            $disease_id = App::call()->request->getParams()['disease_id'];
            $item->disease_id = $disease_id;
        }
        if (isset(App::call()->request->getParams()['biospecimen_type_id'])) {
            $biospecimen_type_id = App::call()->request->getParams()['biospecimen_type_id'];
            $item->biospecimen_type_id = $biospecimen_type_id;
        }
        if (isset(App::call()->request->getParams()['mod_id'])) {
            $mod_id = App::call()->request->getParams()['mod_id'];
            $item->mod_id = $mod_id;
        }
        if (isset(App::call()->request->getParams()['sample_count'])) {
            $sample_count = App::call()->request->getParams()['sample_count'];
            $item->sample_count = $sample_count;
        }


        $result = App::call()->diseasesBiospecimenTypesRepository->save($item);
        echo $result;
    }

    public function actionDeleteBiospecimenTypesOrder()
    {
        //$this->checkAccess();
        if (isset(App::call()->request->getParams()['id'])) {
            $id = App::call()->request->getParams()['id'];
            $diseasesBiospecimenType = App::call()->diseasesBiospecimenTypesRepository->getObject($id);
            $result = App::call()->diseasesBiospecimenTypesRepository->delete($diseasesBiospecimenType);
            echo json_encode(['result' => $result]);
        } else {
            echo json_encode(['result' => "аккуратеней братан, параметр передавть надо "]);
        }
    }


    public function actionDeleteDiseasesOrder()
    {
        //$this->checkAccess();
        if (isset(App::call()->request->getParams()['id'])) {
            $id = App::call()->request->getParams()['id'];
            $orderDisease = App::call()->orderDiseasesRepository->getObject($id);
            $result = App::call()->orderDiseasesRepository->delete($orderDisease);
            echo json_encode(['result' => $result]);
        } else {
            echo json_encode(['result' => "аккуратеней братан, параметр передавть надо "]);
        }
    }


    // Создание модификации

    public function actionCreateMod()
    {
        if (isset(App::call()->request->getParams()['id'])) {
            $id = App::call()->request->getParams()['id'];
            $item = App::call()->sampleModRepository->getObject($id);
        } else {
            $item = new SampleMod();
        }

        if (isset(App::call()->request->getParams()['modification'])) {
            $modification = App::call()->request->getParams()['modification'];
            $item->modification = $modification;
        }

        $result = App::call()->sampleModRepository->save($item);
        echo $result;
    }

    public function actionErrorDate()
    {
//       $offres = App::call()->offerRepository->chechDate();
//
//       for($i = 0; $i < count($offres); $i++ ){
//           $offer = App::call()->offerRepository->getObject($offres[$i]['id']);
//           $offer->date_valid = date('Y-m-d', strtotime('+3 month', strtotime($offer->date_offer)));;
//           App::call()->offerRepository->save($offer);
//       }

    }


    public function actionGetChatByProjid()
    {
        if (isset(App::call()->request->getParams()['proj_id'])) {
            $proj_id = App::call()->request->getParams()['proj_id'];
            $chat = App::call()->chatRepository->getWhere(['proj_id' => $proj_id]);
        }
        echo json_encode(['result' => $chat]);
    }

    public function actionChatSave()
    {
        if (isset(App::call()->request->getParams()['id'])) {
            $id = App::call()->request->getParams()['id'];
            $chat = App::call()->chatRepository->getObject($id);
        } else {
            $chat = new Chat();
        }

        if (isset(App::call()->request->getParams()['proj_id'])) {
            $proj_id = App::call()->request->getParams()['proj_id'];
            $chat->proj_id = $proj_id;
        }

        if (isset(App::call()->request->getParams()['message'])) {
            $message = App::call()->request->getParams()['message'];
            $chat->message = $message;
        }


        if (isset(App::call()->request->getParams()['sender'])) {
            $sender = App::call()->request->getParams()['sender'];
            $chat->sender = $sender;
        }

        if (isset(App::call()->request->getParams()['viewed'])) {
            $viewed = App::call()->request->getParams()['viewed'];
            $chat->viewed = $viewed;
        }

        if (isset(App::call()->request->getParams()['deleted'])) {
            $deleted = App::call()->request->getParams()['deleted'];
            $chat->deleted = $deleted;
        }


        $result = App::call()->chatRepository->save($chat);
        $array = App::call()->chatRepository->getWhere(['id' => $result]);
        echo json_encode(['result' => $array[0]]);

    }


}




