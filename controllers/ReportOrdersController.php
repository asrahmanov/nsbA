<?php

namespace app\controllers;

use app\engine\App;
use app\models\entities\Access;
use app\models\entities\Mail;


class ReportOrdersController extends Controller
{

    protected $script_id;
    protected $table;

    public function actionGenerateReportClient()
    {
        $this->script_id = 0;
        $this->script_id = App::call()->request->getParams()['script_id'];

        $staff = App::call()->ordersRepository->getOrdersStaff();


        $fr_date = DATE('Y-m-d');
        $fr_date = strtotime('-4 WEEK', strtotime($fr_date));
//        $fr_date = strtotime('-3 WEEK', strtotime($fr_date));
        $fr_date = date('Y-m-d', $fr_date);

        $orders = App::call()->ordersRepository->getByScriptIdLimit($this->script_id, $fr_date);


        $action = $scripts_staff_id = App::call()->request->getParams()['action'];

        if ($action == 'send') {


            $this->table .= "  <table class='table' id='table' style='
                width: 100%;
                margin-bottom: 1.5rem;
                color: #212529;
                font-family: Arial;
                font-size: 15px;
                border: 1px solid #000;
                width: 100%;
                '>
                <thead style='
                background-color: #4074b0;
                color: #fff;
                '>   
                    <th style='text-align: center; vertical-align: center; border: 1px solid #000;'> Date of request</th>
                    <th style='text-align: center; vertical-align: center; border: 1px solid #000;' >Internal id</th>
                    <th style='text-align: center; vertical-align: center; border: 1px solid #000;'>External id</th>
                    <th style='text-align: center; vertical-align: center; border: 1px solid #000;'>Title</th>
                    <th style='text-align: center; vertical-align: center; border: 1px solid #000;'>Manager that submitted FR </th>
                    <th style='text-align: center; vertical-align: center; border: 1px solid #000;'>Status</th>
                    </thead>
                    <tbody>";

            for ($i = 0; $i < count($orders); $i++) {

                if ($i % 2 === 0) {
                    $color = 'background-color: #ffffff;';
                } else {
                    $color = 'background-color: #e0eaf5;';
                }


                // Получения имени сотрудника
                $staffUser = $this->getStaffFilter($orders[$i]['proj_id'], $staff);
                // Полчения всех изменений по статусу определенной заявки
                $statusAllProject = App::call()->ordersStatusActionsRepository->getStatusAllByProjectId($orders[$i]['proj_id']);


                $status = $this->getStatusforClient(0, $statusAllProject, 1);


                $this->table .= "
            <tr  style='border: 1px solid #000; {$color}' >
            <td style = 'text-align: center; vertical-align: center; border: 1px solid #000;' >{$orders[$i]['fr_date']}</td >
            <td style = 'text-align: center; vertical-align: center; border: 1px solid #000;' >{$orders[$i]['internal_id']} </td >
            <td style = 'text-align: center; vertical-align: center; border: 1px solid #000;' >{$orders[$i]['external_id']}</td >
            <td style = 'text-align: center; vertical-align: center; border: 1px solid #000;' >{$orders[$i]['project_name']}</td >
            <td style = 'text-align: center; vertical-align: center; border: 1px solid #000;' >{$staffUser}</td >
            <td style = 'text-align: center; vertical-align: center; border: 1px solid #000;' >{$status}</td >
            </tr>
            ";
            }

            $this->table .= "</tbody></table>";

            echo "{$this->table}";

            if ($this->script_id == 86) {
//                $mailSend = new Mail();
//                $mailSend->email = 'banked@ispecimen.com; ahayes@ispecimen.com; ddimezza@ispecimen.com; ablack@ispecimen.com; ehubbard@ispecimen.com; jraygoza@ispecimen.com; bd@i-bios.com';
//                $mailSend->send_time = DATE('Y-m-d H:i:s');
//                $mailSend->subject = "Weekly updates I-BIOScompany";
//                $mailSend->send = "NO";
//                $mailSend->text_mail = "Here is our weekly update on the status of all new requests we received from you in last 30 days." . PHP_EOL . "{$this->table}";
//                App::call()->mailRepository->save($mailSend);

                $mailSend = new Mail();
                $mailSend->email = 'asrahmanov@gmail.com';
                $mailSend->send_time = DATE('Y-m-d H:i:s');
                $mailSend->subject = "Weekly updates I-BIOScompany";
                $mailSend->send = "NO";
                $mailSend->text_mail = "Here is our weekly update on the status of all new requests we received from you in last 30 days." . PHP_EOL . "{$this->table}";
                App::call()->mailRepository->save($mailSend);

            }


        } else {
            echo $this->renderTemplate('404', $params = [
                'error' => ''
            ]);
        }

    }

    public function getStaffFilter($proj_id, $staff)
    {
        for ($i = 0; $i < count($staff); $i++) {
            if ($staff[$i]['proj_id'] == $proj_id) {
                return $staff[$i]['name'];
            }
        }
        return '-';
    }

    public function getStatusforClient($orders_status_id = 0, $statusAllProject, $i = 1)
    {


        $count = count($statusAllProject); // Сколько всего изменений
        if ($orders_status_id == 0) {

            if ($count == 0) {
                return '---';
            } else {
                $orders_status_id = $statusAllProject[$count - $i]['orders_status_id'];
            }
        }


        $i++;

        $FeasilibilityEvaluationInProgress = [1, 2, 3, 4, 5, 46, 47, 6, 7, 8, 9, 10, 34, 11, 12, 13, 14, 15, 35, 16, 17, 18, 19, 20, 52];
        $ProposalIsPassingInternalApproval = [21, 22, 23, 24, 25, 40, 41, 42, 43, 44, 45, 48];
        $OurQuoteWasProvidedYourFeedbackIsMuchAppreciated = [27];
        $Declined = [29];
        $POreceived = [33];
        $QuestionsSent = [38];
        $none = [26, 28, 30, 31, 36, 37, 39, 49, 50, 51];


        if (in_array($orders_status_id, $FeasilibilityEvaluationInProgress)) {
            return "Feasilibility evaluation in progress.";
        }

        if (in_array($orders_status_id, $ProposalIsPassingInternalApproval)) {
            return "Proposal is passing internal approval.";
        }

        if (in_array($orders_status_id, $OurQuoteWasProvidedYourFeedbackIsMuchAppreciated)) {
            return "Our quote was provided, your feedback is much appreciated.";
        }

        if (in_array($orders_status_id, $Declined)) {
            return "Declined.";
        }

        if (in_array($orders_status_id, $POreceived)) {
            return "PO received.";
        }

        if (in_array($orders_status_id, $QuestionsSent)) {
            return "Questions sent.";
        }

        $orders_status_id = $statusAllProject[$count - $i]['orders_status_id'];

        return $this->getStatusforClient($orders_status_id, $statusAllProject, $i);

    }

}
