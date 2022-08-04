<?php

namespace app\controllers;

use app\engine\App;
use app\models\entities\Mail;


class MailController extends Controller
{
    protected $defaultAction = 'mail';


    public function actionMail()
    {
        echo "упс";
    }


    public function actionSend()
    {
        $user_id = App::call()->session->getSession('user_id');
        $user = App::call()->usersRepository->getOne($user_id);

        $email = $user['email'];
        $subject = 'FR TABLE';

        $head = " <table class=\"table table-striped table-bordered file-export\" id=\"datatable_fr\" border=\"1\">
                                        <thead>
                                        <th>Date</th>
                                        <th>linked fr id</th>
                                        <th>internal id</th>
                                        <th>external id</th>
                                        <th>project name</th>
                                        <th>Original text</th> <!--  project details-->
                                        <th>comments</th>
                                        <th>Russian text</th>
                                        <th>status</th>
                                        <th>status date</th>
                                        <th>Specific feature</th>
                                        <th>User</th>
                                        <th>script number</th>
                                        <th>priority</th>
                                        <th>I-BIOSDeadline</th>
                                        <th>Quote mount</th>
                                        <th>Delivery Deadline</th>
                                        <th>Proposal Deadline</th>
                                            <th>Клиент</th>
                                            <th>Менеджеры</th>
                                            <th>Проектный отдел</th>
                                            <th>ЛТО</th>
                                            <th>Отдел ВЕД</th>
                                            <th>Руководство</th>   
                                        </thead>";

        $body = "";


        $footer = "<tfoot>
                                        <tr>
                                            <th>Date</th>
                                            <th>linked fr id</th>
                                            <th>internal id</th>
                                            <th>external id</th>
                                            <th>project</th>
                                            <th>Original text</th> 
                                            <th>comments</th>
                                            <th>Russian text</th>
                                            <th>status</th>
                                            <th>status date</th>
                                            <th>Specific feature</th>
                                            <th>User</th>
                                            <th>script number</th>
                                            <th>priority</th>
                                            <th>I-BIOSDeadline</th>
                                            <th>Quote mount</th>
                                            <th>Delivery Deadline</th>
                                            <th>Proposal Deadline</th>
                                             <th>Клиент</th>
                                            <th>Менеджеры</th>
                                            <th>Проектный отдел</th>
                                            <th>ЛТО</th>
                                            <th>Отдел ВЕД</th>
                                            <th>Руководство</th>            
                                            
                                        </tr>
                                        </tfoot>
                                    </table>";

//        $text = urldecode($_POST['table']);

        $searchParams = App::call()->request->getParams();
        $params = [];
        foreach ($searchParams as $key => $value) {
            if ($key != '/mail/send') {
                if ($key == 'fr_status') {
                    $newValue = App::call()->frstatusRepository->getWhere(['fr_status_values' => $value]);
                    $params[$key] = $newValue[0]['fr_status_id'];
                } else if ($key == 'fr_script_id') {
                    $script = App::call()->companyRepository->getWhere(['script' => $value]);
                    $params[$key] = $script[0]['script_id'];
                } else if ($key == 'status_client') {
                    $status_client = App::call()->ordersStatusRepository->getWhere([
                        'department_id' => 7,
                        'status_name' => $value
                    ]);
                    $params[$key] = $status_client[0]['id'];
                } else if ($key == 'status_manager') {
                    $status_manager = App::call()->ordersStatusRepository->getWhere([
                        'department_id' => 2,
                        'status_name' => $value
                    ]);
                    $params[$key] = $status_manager[0]['id'];
                } else if ($key == 'status_project') {
                    $status_project = App::call()->ordersStatusRepository->getWhere([
                        'department_id' => 3,
                        'status_name' => $value
                    ]);
                    $params[$key] = $status_project[0]['id'];
                } else if ($key == 'status_lpo') {
                    $status_project = App::call()->ordersStatusRepository->getWhere([
                        'department_id' => 4,
                        'status_name' => $value
                    ]);
                    $params[$key] = $status_project[0]['id'];
                } else if ($key == 'status_ved') {
                    $status_ved = App::call()->ordersStatusRepository->getWhere([
                        'department_id' => 5,
                        'status_name' => $value
                    ]);
                    $params[$key] = $status_ved[0]['id'];
                } else if ($key == 'status_boss') {
                    $status_boss = App::call()->ordersStatusRepository->getWhere([
                        'department_id' => 6,
                        'status_name' => $value
                    ]);
                    $params[$key] = $status_boss[0]['id'];
                } else {
                    $params[$key] = $value;
                }
            }
        }

        $orders = App::call()->ordersRepository->getWhereOrder($params, 'proj_id', 'DESC');

        $status = [];
        $fr_status = App::call()->frstatusRepository->getAll();
        for ($i = 0; $i < count($fr_status); $i++) {
            $status[$fr_status[$i]['fr_status_id']] = $fr_status[$i]['fr_status_values'];
        }

        $users = [];
        $nbs_users = App::call()->usersRepository->getAll();
        for ($i = 0; $i < count($nbs_users); $i++) {
            $users[$nbs_users[$i]['id']] = $nbs_users[$i]['lasttname'] . ' ' . $nbs_users[$i]['firstname'] . ' ' . $nbs_users[$i]['patronymic'];
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


        for ($i = 0; $i < count($orders); $i++) {


            if (isset($users[$orders[$i]['answering_id']])) {
                $orders[$i]['answering_id'] = $users[$orders[$i]['answering_id']];
            } else {
                $orders[$i]['answering_id'] = 'Не закреплен';
            }


            if (isset($status[$orders[$i]['fr_status']])) {
                $orders[$i]['fr_status'] = $status[$orders[$i]['fr_status']];
            } else {
                $orders[$i]['fr_status'] = $orders[$i]['fr_status'];
            }


            if (isset($priorityArray[$orders[$i]['priority_id']])) {
                $orders[$i]['priority_id'] = $priorityArray[$orders[$i]['priority_id']];
            } else {
                $orders[$i]['priority_id'] = 'Приоритет не установлен';
            }


            if (isset($quotes[$orders[$i]['proj_id']])) {
                $value_mount = $quotes[$orders[$i]['proj_id']];
            } else {
                $value_mount = 0;
            }

            $status_client = $orderStatus[$orders[$i]['status_client']];
            $status_manager = $orderStatus[$orders[$i]['status_manager']];
            $status_project = $orderStatus[$orders[$i]['status_project']];
            $status_lpo = $orderStatus[$orders[$i]['status_lpo']];
            $status_ved = $orderStatus[$orders[$i]['status_ved']];
            $status_boss = $orderStatus[$orders[$i]['status_boss']];


            $body = $body . "<tr>
                                            <td>{$orders[$i]['fr_date']}</td>
                                            <td>{$orders[$i]['linked_fr_id']}</td>
                                            <td><a target='_blank' href='http://crm.i-bios.com/orders/info/?idFR={$orders[$i]['proj_id']}'>{$orders[$i]['internal_id']}</a></td>
                                            <td>{$orders[$i]['external_id']}</td>
                                            <td>{$orders[$i]['project_name']}</td>
                                            <td>{$orders[$i]['project_details']}</td>
                                            <td>{$orders[$i]['comments']}</td>
                                            <td>{$orders[$i]['feas_russian']}</td>
                                            <td>{$orders[$i]['fr_status']}</td>
                                            <td>{$orders[$i]['fr_status_date']}</td>
                                            <td>{$orders[$i]['clinical_inclusion']}</td>
                                            <td>{$orders[$i]['clinical_inclusion']}</td>
                                            <td>{$orders[$i]['answering_id']}</td>
                                            <td>{$orders[$i]['script_number']}</td>
                                            <td>{$orders[$i]['priority_id']}</td>
                                            <td>{$orders[$i]['deadline']}</td>
                                            <td>{$value_mount}</td>
                                            <td>{$orders[$i]['deadline_post']}</td>
                                            <td>{$orders[$i]['deadline_quote']}</td>
                                            
                                            <td>{$status_client}</td>
                                            <td>{$status_manager}</td>
                                            <td>{$status_project}</td>
                                            <td>{$status_lpo}</td>
                                            <td>{$status_ved}</td>
                                            <td>{$status_boss}</td>
                                       
                                        
                                        </tr>";
        }

        $text = $head . $body . $footer;
        $mailSend = new Mail();


        if (($email !== '') && !is_null($email)) {
            $mailSend->email = $email;
            $mailSend->subject = $subject;
            $mailSend->text_mail = $text;
            $mailSend->send_time = date('Y-m-d H:i:s');
            $mailSend->send = 'NO';
        }
        App::call()->mailRepository->save($mailSend);

    }


    public function actionSendKick()
    {

        $user_id = App::call()->request->getParams()['user_id'];
        $proj_id = App::call()->request->getParams()['proj_id'];

        $email = App::call()->usersRepository->getEmail($user_id);

        $order = App::call()->ordersRepository->GetOrdersOne($proj_id);
        $subject = "ВНИМАНИЕ!!!  Запрос на квотирование запроса {$order['proj_id']}  {$order['project_name']}";
        $text = "Добрый день, В I-BIOSPlatform Вам поступил запрос на квотирование 
         {$order['proj_id']} 
                {$order['project_name']}
                
                {$order['feas_russian']}
                ";

        if (is_array($email)) {
            $email = implode(', ', $email);
        }

        App::call()->mailRepository->sendMail($email, $subject, $text);

    }


    public function actionSendNewInfo()
    {

        $proj_id = App::call()->request->getParams()['proj_id'];
        $order = App::call()->ordersRepository->GetOrdersOne($proj_id);

        $subject = "ВНИМАНИЕ!!!  Изменилась информация в запросе {$order['proj_id']}  {$order['project_name']}";
        $text = "Добрый день, В I-BIOSPlatform в запросе
         {$order['proj_id']}
         Изменилось информация:
         {$order['project_name']}
         {$order['feas_russian']}";

        $users = App::call()->worksheetsRepository->getUserWorksheets($proj_id);

        for ($i = 0; $i < count($users); $i++) {

            if (is_array($users[$i]['email'])) {
                $users[$i]['email'] = implode(', ', $users[$i]['email']);
            }

            App::call()->mailRepository->sendMail($users[$i]['email'], $subject, $text);

        }


    }

    public function actionSendMailQouteSuccess()
    {

        $proj_id = App::call()->request->getParams()['proj_id'];
        $user_id = App::call()->request->getParams()['user_id'];
        $quote_value= App::call()->request->getParams()['quote_value'];
        $quote_id = App::call()->request->getParams()['quote_id'];

        $order = App::call()->poRepository->GetOrdersOne($proj_id);

        $getSample = App::call()->poquoteRepository->getSample($quote_id);
        $getId = App::call()->poworksheetsRepository->findWorksheet($user_id,$proj_id);

        $url = "http://crm.i-bios.com/poWorksheets/quote/?id={$getId['id']}";

        $sample = '';
        for ($i = 0; $i < count($getSample); $i++) {
            $sample .=  " {$getSample[$i]['biospecimen_type']}  ({$getSample[$i]['biospecimen_type_russian']})
            ";
        }

        $subject = "Согласование для сбора образцов по проекту {$order['po_number']}  {$order['project_name']}";
        $text = "В рамках проекта {$order['po_number']}  {$order['project_name']} 
        вам согласован сбор образцов: {$sample}количество {$quote_value}. 
         <a href='$url'>{$order['po_number']}</a>";

        $email = App::call()->usersRepository->getEmail($user_id);
        $email = $email['email'];


        App::call()->mailRepository->sendMail($email, $subject, $text, 'no' , 0, '');


    }

    public function actionSendManagerPoMail()
    {

        $proj_id = App::call()->request->getParams()['proj_id'];
        $user_id = App::call()->request->getParams()['user_id'];
        $proj_id = App::call()->request->getParams()['proj_id'];
        $subject = App::call()->request->getParams()['subjectMail'];
        $text = App::call()->request->getParams()['textMail'];
        $email_lab = App::call()->request->getParams()['lab'];
        $action = App::call()->request->getParams()['action'];
        $selected_ids = App::call()->request->getParams()['manager_ids'] ?? [];
        $nbs_users = App::call()->usersRepository->getManager();


        for ($j = 0; $j < count($nbs_users); $j++) {
            if (count($selected_ids) > 0 && array_search($nbs_users[$j]['id'], $selected_ids) !== false || count($selected_ids) === 0) {

                $email = App::call()->usersRepository->getEmail($nbs_users[$j]['id']);
                App::call()->mailRepository->sendMail($email['email'], $subject, $text, $action, $proj_id);
                echo "{$email['email']}
                ";
            }
        }
        App::call()->mailRepository->sendMail('ProCor@nbioservice.com', $subject, $text, $action ,$proj_id);
        App::call()->mailRepository->sendMail('Finance@nbioservice.com', $subject, $text, $action ,$proj_id);
        App::call()->mailRepository->sendMail( $email_lab, $subject, $text, $action ,$proj_id);

    }


}
