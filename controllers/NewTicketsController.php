<?php

namespace app\controllers;

use app\engine\App;
use app\models\entities\Mail;
use app\models\entities\NewTickets;
use app\models\entities\NewTicketChats;


class NewTicketsController extends Controller
{
    protected $layout = 'admin';
    protected $folder = 'new_tickets/';
    protected $defaultAction = 'index';

    public function actionIndex()
    {
        $user_id = App::call()->session->getSession('user_id');
        $role_id = App::call()->session->getSession('role_id');
        echo $this->render($this->folder . $this->defaultAction, [
            'user_id' => $user_id,
            'role_id' => $role_id
        ]);
    }

    public function actionObserver()
    {
        $user_id = App::call()->session->getSession('user_id');
        $role_id = App::call()->session->getSession('role_id');
        echo $this->render($this->folder . 'observer', [
            'user_id' => $user_id,
            'role_id' => $role_id
        ]);
    }

    public function actionReport()
    {
        $user_id = App::call()->session->getSession('user_id');
        $role_id = App::call()->session->getSession('role_id');
        echo $this->render($this->folder . 'manager_report', [
            'user_id' => $user_id,
            'role_id' => $role_id
        ]);
    }

    public function actionGraphicReport()
    {
        $dateOne = Date('Y-m-'. '01');
        $dateTwo = Date('Y-m-d');


        echo $this->render($this->folder . 'graphic_report',
            [
                'dateOne' => $dateOne,
                'dateTwo' => $dateTwo,
            ]
        );
    }

    public function actionView()
    {
        $user_id = App::call()->session->getSession('user_id');
        $role = App::call()->session->getSession('role_id');
        $ticket_id = App::call()->request->getParams()['id'];
        $ticket = App::call()->newTicketsRepository->getOne($ticket_id);
        $author = App::call()->usersRepository->getOne($ticket['author_id']);
        $authorFullName = "{$author['lasttname']} {$author['firstname']} {$author['patronymic']}";
        $target = App::call()->usersRepository->getOne($ticket['target_id']);
        $targetFullName = "{$target['lasttname']} {$target['firstname']} {$target['patronymic']}";
        if ($ticket['order_id'] !== null && $ticket['reason'] !== 'manager') {
            $fr = App::call()->ordersRepository->GetOrdersOne($ticket['order_id']);
            $quote_deadline_frags = explode('-', $fr['deadline_quote']);
            $quote_deadline = $quote_deadline_frags[2] . '.' . $quote_deadline_frags[1] . '.' . $quote_deadline_frags[0];
            $status_ved = App::call()->ordersStatusRepository->getObject($fr['status_ved']);
            $fr['status_ved_text'] = $status_ved->status_name;
            $status_lpo = App::call()->ordersStatusRepository->getObject($fr['status_lpo']);
            $fr['status_lpo_text'] = $status_lpo->status_name;
            $status_project = App::call()->ordersStatusRepository->getObject($fr['status_project']);
            $fr['status_project_text'] = $status_project->status_name;
            $quotes = App::call()->quoteRepository->getQuotebyProj($ticket['order_id']);
            foreach ($quotes as $key => $quote) {
                $disease = App::call()->orderDiseasesRepository->getOne($quote['disease_id']);
                $disease_name = App::call()->diseaseRepository->getOne($disease['disease_id']);
                $quotes[$key]['disease'] = "{$disease_name['disease_name']} ({$disease_name['disease_name_russian_old']})";
                $quotes[$key]['disease_id_db'] = $disease['disease_id'];
                $doctor_payments = App::call()->quoteDoctorRepository->getWhere(['quote_id' => $quote['id']]);
                foreach ($doctor_payments as $index => $doctor_payment) {
                    $doctor = App::call()->companiesContactsRepository->getOne($doctor_payment['doc_id']);
                    $doctor_payments[$index]['doc_name'] = "{$doctor['lastname']} {$doctor['firstname']} {$doctor['patronymic']}";
                }
                $quotes[$key]['doctor_payments'] = $doctor_payments;
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
            }
            $inventory = App::call()->worksheetsInvertoryRepository->getByProj($ticket['order_id']);
            $laboratory = App::call()->worksheetsLaboratoryRepository->getByProj($ticket['order_id']);
            $script_id = $fr['fr_script_id'];
            $company = App::call()->companyRepository->getOne($script_id);
            $currency = $company['currency'];
            $company_name = $company['company_name'];
            if ($currency == '') {
                $currency = 'USD';
            } else {
                $currency = App::call()->currencyRepository->getOne($currency);
                $currency = $currency['currency'];
            }
            $order_diseases = App::call()->orderDiseasesRepository->getWhere(['order_id' => $ticket['order_id']]);
            foreach ($order_diseases as $key => $order_disease) {
                $disease = App::call()->diseaseRepository->getOne($order_disease['disease_id']);
                $order_diseases[$key]['disease'] = $disease['disease_name'];
                $order_diseases[$key]['mutation'] = $disease['disease_name_russian_old'];
            }
            $diseases_biospecimen_types = App::call()->diseasesBiospecimenTypesRepository->getWhere(['order_id' => $ticket['order_id']]);
            foreach ($diseases_biospecimen_types as $key => $diseases_biospecimen_type) {
                $biospecimen_type = App::call()->biospecimenTypeRepository->getOne($diseases_biospecimen_type['biospecimen_type_id']);
                $diseases_biospecimen_types[$key]['biospecimen_type'] = $biospecimen_type['biospecimen_type'];
                $modification = App::call()->sampleModRepository->getOne($diseases_biospecimen_type['mod_id']);
                $diseases_biospecimen_types[$key]['modification'] = $modification['modification'];
            }
            $approvals = App::call()->newTicketApprovalsRepository->getWhere(['ticket_id' => $ticket_id]);
            $approved_by_me = false;
            foreach ($approvals as $i => $approval) {
                $approver = App::call()->usersRepository->getOne($approval['user_id']);
                $approvals[$i]['name'] = "{$approver['lasttname']} {$approver['firstname']} {$approver['patronymic']}";
                if ($approval['user_id'] === $user_id && $approval['approvement'] === '1')
                    $approved_by_me = true;
                else if ($approval['user_id'] === $user_id && $approval['approvement'] !== '1')
                    $approved_by_me = false;
            }
            $approved = [
                '4' => '0',
                '5' => '0',
                '56' => '0'
            ];
            foreach ($approvals as $approvement) {
                $approved[$approvement['user_id']] = $approvement['approvement'];
            }
            $files = [];
            $nbs_files = App::call()->filesRepository->getFilesbyProjId($ticket['order_id']);
            for ($i = 0; $i < count($nbs_files); $i++) {
                $files[] = $nbs_files[$i];
            }
            // Quote project data
            $offer = App::call()->offerRepository->getWhere(['proj_id' => $ticket['order_id']]);
//            $offer_ru = App::call()->offerRuRepository->getObject($ticket['order_id']);
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

            // Quote Ru project data
            $offer_ru = App::call()->offerRuRepository->getWhere(['proj_id' => $ticket['order_id']]);
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
            echo $this->render($this->folder . 'view', [
                'role' => $role,
                'ticket' => $ticket,
                'fr' => $fr,
                'quotes' => $quotes,
                'inventory' => $inventory,
                'laboratory' => $laboratory,
                'script_id' => $script_id,
                'currency' => $currency,
                'company_name' => $company_name,
                'order_disease_options' => json_encode($order_diseases, JSON_UNESCAPED_UNICODE),
                'order_diseases_biospecimen' => json_encode($diseases_biospecimen_types, JSON_UNESCAPED_UNICODE),
                'author' => $authorFullName,
                'target' => $targetFullName,
                'user_id' => $user_id,
                'approvals' => $approvals,
                'approved_by_me' => $approved_by_me,
                'approved' => $approved,
                'files' => $files,
                'offer' => json_encode($offer[0], JSON_UNESCAPED_UNICODE),
                'offer_items' => json_encode($offer_items, JSON_UNESCAPED_UNICODE),
                'offer_ru' => json_encode($offer_ru[0], JSON_UNESCAPED_UNICODE),
                'offer_ru_items' => json_encode($offer_ru_items, JSON_UNESCAPED_UNICODE),
                'quote_deadline' => $quote_deadline
            ]);
        } else {
            $worksheets = App::call()->worksheetsRepository->getWhere([
                'proj_id' => $ticket['order_id'],
                'user_id' => $user_id
            ]);
            if (count($worksheets) > 0) {
                $order = App::call()->worksheetsRepository->getJoinOrdersByWorksheetsId($worksheets[0]['id']);
            }
            $order_diseases = App::call()->orderDiseasesRepository->getWhere(['order_id' => $order[0]['proj_id']]);
            foreach ($order_diseases as $key => $order_disease) {
                $disease = App::call()->diseaseRepository->getOne($order_disease['disease_id']);
                $order_diseases[$key]['disease'] = $disease['disease_name'];
                $order_diseases[$key]['mutation'] = $disease['disease_name_russian_old'];
            }
            $diseases_biospecimen_types = App::call()->diseasesBiospecimenTypesRepository->getWhere(['order_id' => $order[0]['proj_id']]);
            foreach ($diseases_biospecimen_types as $key => $diseases_biospecimen_type) {
                $biospecimen_type = App::call()->biospecimenTypeRepository->getOne($diseases_biospecimen_type['biospecimen_type_id']);
                $diseases_biospecimen_types[$key]['biospecimen_type'] = $biospecimen_type['biospecimen_type'];
                $diseases_biospecimen_types[$key]['biospecimen_type_russian'] = $biospecimen_type['biospecimen_type_russian'];
                $modification = App::call()->sampleModRepository->getOne($diseases_biospecimen_type['mod_id']);
                $diseases_biospecimen_types[$key]['modification'] = $modification['modification'];
            }
            $biospecimen_types_res = App::call()->biospecimenTypeRepository->getAll();
            $biospecimen_types = [];
            foreach ($biospecimen_types_res as $key => $biospecimen_types_res_item) {
                $biospecimen_types_res_item['biospecimen_type_id'] = $biospecimen_types_res_item['id'];
                $biospecimen_types[] = $biospecimen_types_res_item;
            }
            echo $this->render($this->folder . 'viewSimple', [
                'ticket' => $ticket,
                'author' => $authorFullName,
                'target' => $targetFullName,
                'user_id' => $user_id,
                'role' => $role,
                'fr' => $order[0],
                'id' => $ticket['order_id'],
                'order' => $order,
                'order_diseases' => json_encode($order_diseases, JSON_UNESCAPED_UNICODE),
                'diseases_biospecimen_types' => json_encode($diseases_biospecimen_types, JSON_UNESCAPED_UNICODE),
                'order_diseases_arr' => $order_diseases,
                'diseases_biospecimen_types_arr' => $diseases_biospecimen_types,
                'biospecimen_types' => json_encode($biospecimen_types, JSON_UNESCAPED_UNICODE)
            ]);
        }
    }

    public function actionReminders()
    {
        $user_id = App::call()->session->getSession('user_id');
        echo $this->render($this->folder . 'reminders', [
            'user_id' => $user_id
        ]);
    }

    public function actionRatings()
    {
        $user_id = App::call()->session->getSession('user_id');
        $role_id = App::call()->session->getSession('role_id');
        echo $this->render($this->folder . 'ratings', [
            'user_id' => $user_id,
            'role_id' => $role_id
        ]);
    }

    public function actionRatingsTable()
    {
        $tickets = App::call()->newTicketsRepository->getAll();
        $reminders = ['data' => []];
        $my_role = App::call()->session->getSession('role_id');
        $my_id = App::call()->session->getSession('user_id');
        $users = App::call()->usersRepository->getAll();
        $userNamesByid = new \stdClass();
        foreach ($users as $user)
            $userNamesByid->{$user['id']} = "{$user['lasttname']} {$user['firstname']} {$user['patronymic']}";
        foreach ($tickets as $i => $ticket) {
            if ($ticket['order_id'] && $ticket['target_id'] !== '1' && $ticket['reason']) {
                $project = App::call()->ordersRepository->getOne($ticket['order_id']);
                $tickets[$i]['internal_id'] = $project['internal_id'];
                $tickets[$i]['responsible_admin'] = $project['answering_id'];
                $ratings = App::call()->newTicketRatingRepository->getWhere(['ticket_id' => $ticket['id']]);
                $ratings_count = count($ratings);
                $tickets[$i]['comment'] = '';
                if ($ratings_count) {
                    $ratings_sum = 0;
                    foreach ($ratings as $rating) {
                        $ratings_sum += $rating['rating'];
                        if ($rating['comment'] != '')
                            $tickets[$i]['comment'] .= "<i>{$rating['comment']}</i><br>{$userNamesByid->{$rating['user_id']}}";
                    }
                    $tickets[$i]['ratings'] = $ratings_sum / $ratings_count;
                } else {
                    $tickets[$i]['ratings'] = 0;
                }
                if ($my_id === '1' || $my_role === '2' || $my_role === '6' ||
                    $my_role === '1' && $my_id === $project['answering_id'])
                    $reminders['data'][] = $tickets[$i];
            }
        }
        echo json_encode($reminders);
    }

    public function actionGetMy()
    {
        $status = 'all';
        if (isset(App::call()->request->getParams()['status'])) {
            $status = App::call()->request->getParams()['status'];
        }
        $user_id = App::call()->session->getSession('user_id');
        $cond = ['author_id' => $user_id];
        if ($status !== 'all')
            if ($status === 'open')
                $cond = ['author_id' => $user_id, 'done' => '0'];
            else if ($status === 'closed')
                $cond = ['author_id' => $user_id, 'done' => '1'];
        $array = App::call()->newTicketsRepository->getWhere($cond);
        $in_cond = ['target_id' => $user_id];
        if ($status !== 'all')
            if ($status === 'open')
                $in_cond = ['target_id' => $user_id, 'done' => '0'];
            else if ($status === 'closed')
                $in_cond = ['target_id' => $user_id, 'done' => '1'];
        $in_array = App::call()->newTicketsRepository->getWhere($in_cond);
        if (in_array($user_id, ['5', '56'])) {
            $status44_cond = ['target_id' => '4'];
            if ($status !== 'all')
                if ($status === 'open')
                    $status44_cond = ['target_id' => '4', 'done' => '0'];
                else if ($status === 'closed')
                    $status44_cond = ['target_id' => '4', 'done' => '1'];
            $status44_array = App::call()->newTicketsRepository->getWhere($status44_cond);
            $tickets = array_merge($array, $in_array, $status44_array);
        } else {
            $tickets = array_merge($array, $in_array);
        }
        foreach ($tickets as $i => $ticket) {
            $chat = App::call()->newTicketChatsRepository->getWhere(['ticket_id' => $ticket['id']]);
            $tickets[$i]['chat'] = $chat;
            if ($ticket['order_id']) {
                $order = App::call()->ordersRepository->getObject($ticket['order_id']);
                $tickets[$i]['project_name'] = $order->project_name;
                $tickets[$i]['internal_id'] = $order->internal_id;
                $tickets[$i]['fr_date'] = $order->fr_date;

                if ($order->deadline) {
                    $deadline_frags = explode('-', $ticket['deadline']);
                    $tickets[$i]['deadline'] = "{$deadline_frags[2]}.{$deadline_frags[1]}.{$deadline_frags[0]}";

                    //  для ремайндера
                    $deadline_frags_remainder = explode('-', $order->deadline);
                    $tickets[$i]['deadline_remainder'] = "{$deadline_frags_remainder[2]}.{$deadline_frags_remainder[1]}.{$deadline_frags_remainder[0]}";

                } else {
                    $tickets[$i]['deadline'] = '';
                    $tickets[$i]['deadline_remainder'] = '';
                }

                $company = App::call()->companyRepository->getObject($order->fr_script_id);
                $tickets[$i]['company_name'] = $company->company_name;
            } else {
                $tickets[$i]['company_name'] = '';
                $tickets[$i]['project_name'] = '';
                $tickets[$i]['internal_id'] = '';
                $tickets[$i]['fr_date'] = '';
            }
            $approved = [
                '4' => '0',
                '5' => '0',
                '56' => '0'
            ];
            $approved_dates = [
                '4' => false,
                '5' => false,
                '56' => false
            ];
            $approvements = App::call()->newTicketApprovalsRepository->getWhere(['ticket_id' => $ticket['id']]);
            foreach ($approvements as $approvement) {
                $approved[$approvement['user_id']] = $approvement['approvement'];
                $app_datetime = $approvement['updated_at'];
                $app_datetime_frags = explode(' ', $app_datetime);
                $app_date_frags = explode('-', $app_datetime_frags[0]);
                $app_time_frags = explode(':', $app_datetime_frags[1]);
                $app_date = "{$app_date_frags[2]}.{$app_date_frags[1]}.{$app_date_frags[0]} {$app_time_frags[0]}:{$app_time_frags[1]}";
                $approved_dates[$approvement['user_id']] = $app_date;
            }
            $tickets[$i]['approved'] = $approved;
            $tickets[$i]['approved_dates'] = $approved_dates;
            $ratings = App::call()->newTicketRatingRepository->getWhere(['ticket_id' => $ticket['id']]);
            $ratings_count = count($ratings);
            if ($ratings_count) {
                $ratings_sum = 0;
                foreach ($ratings as $rating)
                    $ratings_sum += $rating['rating'];
                $tickets[$i]['ratings'] = $ratings_sum / $ratings_count;
            } else {
                $tickets[$i]['ratings'] = 0;
            }
            if (!$ticket['target_id']) {
                $target_managers = App::call()->newTicketTargetRepository->getWhere(['ticket_id' => $ticket['id']]);
                $tickets[$i]['target_managers'] = $target_managers;
            }
        }
        echo json_encode(['result' => $tickets]);
    }

    public function actionGetAll()
    {
        $tickets = App::call()->newTicketsRepository->getAll();
        foreach ($tickets as $i => $ticket) {
            $chat = App::call()->newTicketChatsRepository->getWhere(['ticket_id' => $ticket['id']]);
            $tickets[$i]['chat'] = $chat;
            if ($ticket['order_id']) {
                $order = App::call()->ordersRepository->getObject($ticket['order_id']);
                $tickets[$i]['fr_date'] = $order->fr_date;
                $tickets[$i]['project_name'] = $order->project_name;
                $tickets[$i]['internal_id'] = $order->internal_id;
                $tickets[$i]['deadline_quote'] = $order->deadline_quote;
                $company = App::call()->companyRepository->getObject($order->fr_script_id);
                $tickets[$i]['company_name'] = $company->company_name;

            } else {
                $tickets[$i]['company_name'] = '';
                $tickets[$i]['project_name'] = '';
                $tickets[$i]['internal_id'] = '';
                $tickets[$i]['fr_date'] = '';
                $approved = [
                    '4' => '0',
                    '5' => '0',
                    '56' => '0'
                ];
                $approved_dates = [
                    '4' => false,
                    '5' => false,
                    '56' => false
                ];
                $approvements = App::call()->newTicketApprovalsRepository->getWhere(['ticket_id' => $ticket['id']]);
                foreach ($approvements as $approvement) {
                    $approved[$approvement['user_id']] = $approvement['approvement'];
                    $app_datetime = $approvement['updated_at'];
                    $app_datetime_frags = explode(' ', $app_datetime);
                    $app_date_frags = explode('-', $app_datetime_frags[0]);
                    $app_time_frags = explode(':', $app_datetime_frags[1]);
                    $app_date = "{$app_date_frags[2]}.{$app_date_frags[1]}.{$app_date_frags[0]} {$app_time_frags[0]}:{$app_time_frags[1]}";
                    $approved_dates[$approvement['user_id']] = $app_date;
                }
                $tickets[$i]['approved'] = $approved;
                $tickets[$i]['approved_dates'] = $approved_dates;
            }
        }
        echo json_encode(['result' => $tickets]);
    }

    public function actiongetAllCheckVacation()
    {

        $dateOne = App::call()->request->getParams()['dateOne'];
        $dateTwo = App::call()->request->getParams()['dateTwo'];

        $tickets = App::call()->newTicketsRepository->getByDateTwo($dateOne,$dateTwo);
        $ok_tickets = [];
        foreach ($tickets as $i => $ticket) {
            $check = App::call()->vacationRepository->checkManagerVacation($ticket['created_at'], $ticket['target_id']);
            if ($check[0]["total"] === '0') {
                $chat = App::call()->newTicketChatsRepository->getWhere(['ticket_id' => $ticket['id']]);
                $tickets[$i]['chat'] = $chat;
                if ($ticket['order_id']) {
                    $order = App::call()->ordersRepository->getObject($ticket['order_id']);
                    $tickets[$i]['fr_date'] = $order->fr_date;
                    $tickets[$i]['project_name'] = $order->project_name;
                    $tickets[$i]['internal_id'] = $order->internal_id;
                    $tickets[$i]['deadline_quote'] = $order->deadline_quote;
                    $company = App::call()->companyRepository->getObject($order->fr_script_id);
                    $tickets[$i]['company_name'] = $company->company_name;

                } else {
                    $tickets[$i]['company_name'] = '';
                    $tickets[$i]['project_name'] = '';
                    $tickets[$i]['internal_id'] = '';
                    $tickets[$i]['fr_date'] = '';
                    $approved = [
                        '4' => '0',
                        '5' => '0',
                        '56' => '0'
                    ];
                    $approved_dates = [
                        '4' => false,
                        '5' => false,
                        '56' => false
                    ];
                    $approvements = App::call()->newTicketApprovalsRepository->getWhere(['ticket_id' => $ticket['id']]);
                    foreach ($approvements as $approvement) {
                        $approved[$approvement['user_id']] = $approvement['approvement'];
                        $app_datetime = $approvement['updated_at'];
                        $app_datetime_frags = explode(' ', $app_datetime);
                        $app_date_frags = explode('-', $app_datetime_frags[0]);
                        $app_time_frags = explode(':', $app_datetime_frags[1]);
                        $app_date = "{$app_date_frags[2]}.{$app_date_frags[1]}.{$app_date_frags[0]} {$app_time_frags[0]}:{$app_time_frags[1]}";
                        $approved_dates[$approvement['user_id']] = $app_date;
                    }
                    $tickets[$i]['approved'] = $approved;
                    $tickets[$i]['approved_dates'] = $approved_dates;
                }
                $ok_tickets[] = $tickets[$i];
            }
        }
        echo json_encode(['result' => $ok_tickets]);
    }

    public function actionGetByOrderId()
    {
        $order_id = App::call()->request->getParams()['order_id'];
        $tickets = App::call()->newTicketsRepository->getWhere(['order_id' => $order_id]);
        foreach ($tickets as $i => $ticket) {
            if ($ticket['reason'] !== 'manager') {
                $chat = App::call()->newTicketChatsRepository->getWhere(['ticket_id' => $ticket['id']]);
                $tickets[$i]['chat'] = $chat;
            }
        }
        echo json_encode(['result' => $tickets]);
    }

    public function actionGetByManagerId()
    {
        $manager_id = App::call()->request->getParams()['manager_id'];
        $tickets = App::call()->newTicketsRepository->getWhere(['target_id' => $manager_id]);
        foreach ($tickets as $i => $ticket) {
            $chat = App::call()->newTicketChatsRepository->getWhere(['ticket_id' => $ticket['id']]);
            $tickets[$i]['chat'] = $chat;
            if ($ticket['order_id']) {
                $order = App::call()->ordersRepository->getObject($ticket['order_id']);
                $tickets[$i]['project_name'] = $order->project_name;
                $tickets[$i]['internal_id'] = $order->internal_id;
                $tickets[$i]['fr_date'] = $order->fr_date;
                if ($order->deadline_quote) {
                    $deadline_frags = explode('-', $order->deadline_quote);
                    $tickets[$i]['deadline_quote'] = "{$deadline_frags[2]}.{$deadline_frags[1]}.{$deadline_frags[0]}";
                } else {
                    $tickets[$i]['deadline_quote'] = '';
                }
                $company = App::call()->companyRepository->getObject($order->fr_script_id);
                $tickets[$i]['company_name'] = $company->company_name;
            }
        }
        echo json_encode(['result' => $tickets]);
    }

    public function actionSave()
    {
        $user_id = App::call()->session->getSession('user_id');
        if (isset(App::call()->request->getParams()['id'])) {
            $id = App::call()->request->getParams()['id'];
            $ticket = App::call()->newTicketsRepository->getObject($id);
        } else {
            $ticket = new NewTickets();
            $ticket->author_id = $user_id;
            $target_id = App::call()->request->getParams()['target_id'];
            $user = App::call()->usersRepository->getOne($target_id);
            $email = $user['email'];
            $mailSend = new Mail();
            if (($email !== '') && !is_null($email)) {
                $mailSend->email = $email;
                $mailSend->subject = "В nbs-platforms создана новая задача";
                $mailSend->text_mail = "В nbs-platforms создана новая <a href='http://crm.i-bios.com/newTickets/index'>задача</a>";
                $mailSend->send_time = date('Y-m-d H:i:s');
                $priority = App::call()->request->getParams()['priority'];
                if (!$priority && date('H:i') > '18:30') {
                    $mailSend->send_time = date('Y-m-d 09:00:00', strtotime(' +1 day'));
                } else if (!$priority && date('H:i') < '09:00') {
                    $mailSend->send_time = date('Y-m-d 09:00:00');
                }
                $mailSend->send = 'NO';
            }
            App::call()->mailRepository->save($mailSend);
        }

        if (isset(App::call()->request->getParams()['target_id'])) {
            $target_id = App::call()->request->getParams()['target_id'];
            $ticket->target_id = $target_id;
        }
        if (isset(App::call()->request->getParams()['title'])) {
            $title = App::call()->request->getParams()['title'];
            $ticket->title = $title;
        }
        if (isset(App::call()->request->getParams()['message'])) {
            $message = App::call()->request->getParams()['message'];
            $ticket->message = $message;
        }

        if (isset(App::call()->request->getParams()['done'])) {
            $done = App::call()->request->getParams()['done'];
            if ($done === '1') {

                // Кол-во баллов поставленое Админом
                $score = App::call()->request->getParams()['score'];

                if ($score === '10')
                    $ticket->closed_status = 'ok';
                if ($score === '5')
                    $ticket->closed_status = 'late';
                if ($score === '0')
                    $ticket->closed_status = 'fail';

                // Получаем баллы выставленные автоматом
                $sumScore = App::call()->ticketsScoreRepository->getScore($id);
                $scoreTotal = $sumScore[0]['score'] + $score;

                if($scoreTotal > 25) {
                    $scoreTotal = 25;
                }

                $ticket->closed_at = date('Y-m-d H:i:s');

                // Зафиксируем  общее кол-во баллов
                $ticket->score = $scoreTotal;
                $ticket->done = $done;
            }
        }
        if (isset(App::call()->request->getParams()['deleted'])) {
            $deleted = App::call()->request->getParams()['deleted'];
            $ticket->deleted = $deleted;
        }
        if (isset(App::call()->request->getParams()['order_id'])) {
            $order_id = App::call()->request->getParams()['order_id'];
            $ticket->order_id = $order_id;
        }
        if (isset(App::call()->request->getParams()['priority'])) {
            $priority = App::call()->request->getParams()['priority'];
            $ticket->priority = $priority;
        }
        if (isset(App::call()->request->getParams()['reason'])) {
            $reason = App::call()->request->getParams()['reason'];
            $ticket->reason = $reason;
        }
        if (isset(App::call()->request->getParams()['deadline'])) {
            $deadline = App::call()->request->getParams()['deadline'];
            $ticket->deadline = $deadline;
        }

        $result = App::call()->newTicketsRepository->save($ticket);

        echo json_encode(['result' => $result, 'score' => $scoreTotal]);

    }

    public function actionGetOne()
    {
        $ticket_id = App::call()->request->getParams()['ticket_id'];
        $ticket = App::call()->newTicketsRepository->getOne($ticket_id);
        echo json_encode($ticket);
    }

}