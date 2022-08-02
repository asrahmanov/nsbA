<?php

namespace app\controllers;

use app\engine\App;
use app\models\entities\OfferStatusTrigger;
use Dompdf\Dompdf;

class OfferStatusTriggerController extends Controller
{
    protected $layout = 'admin';
    protected $defaultAction = 'offerStatusTriggers';
    protected $render = 'offerStatusTrigger/offerStatusTriggers';

    public function actionSave()
    {
        if (isset(App::call()->request->getParams()['id'])) {
            $id = App::call()->request->getParams()['id'];
            $item = App::call()->offerStatusTriggerRepository->getObject($id);

        } else {
            $item = New OfferStatusTrigger();
            $item->created_at = DATE('Y-m-d H:i:s');
        }

        if (isset(App::call()->request->getParams()['offer_id'])) {
            $offer_id = App::call()->request->getParams()['offer_id'];
            $item->offer_id = $offer_id;
        }
        if (isset(App::call()->request->getParams()['status'])) {
            $status = App::call()->request->getParams()['status'];
            $item->status = $status;
        }
        if (isset(App::call()->request->getParams()['comment'])) {
            $comment = App::call()->request->getParams()['comment'];
            $item->comment = $comment;

        }


        $result = App::call()->offerStatusTriggerRepository->save($item);
        echo $result;
    }

    public function actionCheckOfferStatus()
    {
        if (isset(App::call()->request->getParams()['offer_id'])) {
            $offer_id = App::call()->request->getParams()['offer_id'];
        } else {
            echo 0;
        }
        if (isset(App::call()->request->getParams()['status'])) {
            $status = App::call()->request->getParams()['status'];
        } else {
            echo 0;
        }
        $status_set = App::call()->ordersRepository->getOne($offer_id);
//        var_dump($status);
//        var_dump($status_set);
        if ($status_set['status_manager'] === $status || $status_set['status_project'] === $status ||
            $status_set['status_lpo'] === $status || $status_set['status_ved'] === $status ||
            $status_set['status_boss'] === $status || $status_set['status_client'] === $status) {
            echo 0;
        } else {
            echo 1;
        }
    }

    public function actionOfferStatusTriggers()
    {
        if (isset(App::call()->request->getParams()['date'])) {
            $date = App::call()->request->getParams()['date'];
        } else {
            $date = date('Y-m-d');
        }
        $triggers_all = App::call()->offerStatusTriggerRepository->getAllJoined($date);
        $users = [];
        $role_id = App::call()->session->getSession('role_id');
        $user_id = App::call()->session->getSession('user_id');
        foreach ($triggers_all as $task) {
            if ($task['task_done_date']) {
                $task_done_date_frags = explode(' ', $task['task_done_date']);
                $task['task_done_date'] = $task_done_date_frags[0];
            }
            if ($task['task_done_date'] == $date || !$task['task_done_date']) {
                $task_date_notime = explode(' ', $task['task_date']);
                $task_date_frags = explode('-', $task_date_notime[0]);
                $task['task_date'] = $task_date_frags[2] . '.' . $task_date_frags[1] . '.' . $task_date_frags[0];
                if (!(isset($users[$task['user_id']])) && ($task['user_id'] == $user_id || $role_id == '2' || $user_id == '1' || $user_id == '2')) {
                    $users[$task['user_id']] = new \stdClass();
                    $users[$task['user_id']]->firstname = $task['firstname'];
                    $users[$task['user_id']]->lasttname = $task['lasttname'];
                    $users[$task['user_id']]->patronymic = $task['patronymic'];
                    $users[$task['user_id']]->tasks_undone = [];
                    $users[$task['user_id']]->tasks_done = [];
                }
                if (isset($users[$task['user_id']])) {
                    if ($task['task_done_date']) {
                        $users[$task['user_id']]->tasks_done[] = $task;
                    } else {
                        $users[$task['user_id']]->tasks_undone[] = $task;
                    }
                }
            }
        }
        echo $this->render($this->render, [
            'pach' => $_SERVER["DOCUMENT_ROOT"],
            'users' => $users,
            'date' => $date
        ]);
    }


    public function actionOfferStatusTriggersPDF()
    {
        if (isset(App::call()->request->getParams()['date'])) {
            $date = App::call()->request->getParams()['date'];
        } else {
            $date = date('Y-m-d');
        }
        if (isset(App::call()->request->getParams()['user_id'])) {
            $user_id = App::call()->request->getParams()['user_id'];
        } else {
            $user_id = 0;
        }
        $triggers_all = App::call()->offerStatusTriggerRepository->getAllJoined($date);
        $users = [];
        foreach ($triggers_all as $task) {
            if ($task['task_date'] === $date || !$task['task_done_date']) {
                $task_date_notime = explode(' ', $task['task_date']);
                $task_date_frags = explode('-', $task_date_notime[0]);
                $task['task_date'] = $task_date_frags[2] . '.' . $task_date_frags[1] . '.' . $task_date_frags[0];
                if (!isset($users[$task['user_id']]) && ($user_id === 0 || $task['user_id'] == $user_id)) {
                    $users[$task['user_id']] = new \stdClass();
                    $users[$task['user_id']]->firstname = $task['firstname'];
                    $users[$task['user_id']]->lasttname = $task['lasttname'];
                    $users[$task['user_id']]->patronymic = $task['patronymic'];
                    $users[$task['user_id']]->tasks = [];
                }
                if (isset($users[$task['user_id']])) {
                    $users[$task['user_id']]->tasks[] = $task;
                }
            }
        }

        ///////////////////////////////////////////////////////////////// PDF
        $date_frags = explode('-', $date);
        $date_daynum = $date_frags[2];
        $months_gen_rus = ['', 'Января', 'Февраля', 'Марта', 'Апреля', 'Мая', 'Июня', 'Июля', 'Августа', 'Сентября', 'Октября', 'Ноября', 'Декабря'];
        $date_month_gen_rus = $months_gen_rus[$date_frags[1]];
        $date_year = $date_frags[0];
        $head = "
                
              <head>
        
         <meta http-equiv=Content-Type content=\"text/html; charset = utf-8\">
        <style>
            *{ font-family: DejaVu Sans !important;}
          </style>
        <meta http-equiv=Content-Type content=\"text/html; charset = utf-8\">
                </head>";
        $style = "
           <style>              
                                            body {
                                            font-family: Helvetica Neue,Helvetica,Arial,sans-serif; 
                                             
                                            }
                                            .xl90 {
                                                background: #fff;
                                                color: black;
                                                font-family: Helvetica Neue,Helvetica,Arial,sans-serif; 
                                                padding: 5px;
                                                text-align: left;
                                                font-size: 12px !important;
                                                
                                            }
                                             .xl91 {
                                                background: #fff;
                                                color: black;
                                                font-family: Helvetica Neue,Helvetica,Arial,sans-serif; 
                                                padding: 5px;
                                                text-align: left;
                                                font-size: 12px !important;
                                                
                                            }
                                            table {
                                                font-size: 10px !important;
                                            }
                                            .offer__table td {
                                                color: black;
                                                font-size: 10px !important;
                                                font-weight: 400;
                                                font-style: normal;
                                                text-decoration: none;
                                                mso-generic-font-family: auto;
                                                mso-font-charset: 204;
                                                mso-number-format: General;
                                                mso-background-source: auto;
                                                mso-protection: locked visible;
                                                mso-rotate: 0;
                                                border-top:.5pt solid windowtext;
                                                font-family: Calibri, sans-serif;
                                                border-top: .5pt solid windowtext;
                                                border-right: .5pt solid windowtext;
                                                border-bottom: .5pt solid windowtext;
                                                border-left: .5pt solid windowtext;
                                                border-spacing: 0;
                                                mso-pattern: black none;
                                                white-space: normal;
                                                text-align: center;
                                                vertical-align: center;
                                            }
                                        </style>";
        $header = "<div class=\"main-content\" id=\"app\">
                    <div class=\"content-wrapper\">
                        <div class=\"row\">
                            <div class=\"col-8\"></div>
                            <div class=\"col-4\" align='right' style='text-align: right'>
                           <table align='right' style='text-align: right' class='xl91'>
                           <tr>
                           <td style='text-align: left; font-size: 14px;'>
                                УТВЕРЖДЕНО:<br>
                                Генеральный директор<br>
                                ООО «Национальный БиоСервис»<br><br>
                                _______________/В.Ю. Пруцкий/<br><br>
                                «{$date_daynum}» $date_month_gen_rus $date_year года
                                </td>
                                </tr>
                                </table>
                            </div>
                            
                        </div>
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                        
                        <div class=\"row\">
                            <div class=\"col-12\" >
                                <h2 style='text-align: center'>СЛУЖЕБНОЕ ЗАДАНИЕ</h2>
                                ";
        if ($user_id === 0) {
            $header = '';
        }
        $tables = '';
        foreach ($users as $user) {
            if ($user_id === 0) {
                $tables .= substr($user->firstname, 0, 2) . '. ' . $user->lasttname . '<br>';
            }
            $tables .= '<table border="1"  class="xl90" style="width: 100%; padding: 5px; font-size: 1.5em;" cellpadding="5" cellspacing="0">
                        <thead>
                        <tr>
                            <td style="width: 10%; text-align: center; !important;"><span style="width: 100%; text-align: center">№</span></td>
                            <td style="width: 30%;">Содержание задания</td>
                            <td style="width: 30%;">Сроки</td>
                            <td style="width: 30%;">Ожидаемые результаты</td>
                            <td style="width: 30%;">Статус</td>
                        </tr>
                        </thead>
                        <tbody>';
            for ($i = 0; $i < count($user->tasks); $i++) {
                $n = $i + 1;
                $status = $user->tasks[$i]['task_done_date'] ? 'Выполнено' : 'Не выполнено';
                $tables .= "<tr>
                                <td>$n</td>
                                <td>{$user->tasks[$i]['comment']}. Запрос <a href=\"http://crm.i-bios.com/orders/info/?idFR={$user->tasks[$i]['offer_id']}\">{$user->tasks[$i]['internal_id']}</a>. Компания: {$user->tasks[$i]['company_name']} external ID: {$user->tasks[$i]['external_id']}  </td>
                                <td>{$user->tasks[$i]['task_date']}</td>
                                <td>{$user->tasks[$i]['status_name']}</td>
                                <td>$status</td>
                            </tr>";
                $credentials = substr($user->firstname, 0, 2) . '. ' . $user->lasttname;

            }
            $tables .= '</tbody>
                    </table>';
        }

        $footer = "<p>Срок выполнения служебного задания: $date_daynum $date_month_gen_rus $date_year г.</p>
                    Настоящее служебное задание получено Работником лично: <br>«{$date_daynum}» $date_month_gen_rus $date_year г. 
                    Подпись: ___________________ / $credentials /";
        if ($user_id === 0) {
            $footer = '';
        }
        $html = "<html>" . $head . $style . $header . $tables . $footer . "</html>";


        $dompdf = new Dompdf();
        $html = mb_convert_encoding($html, 'utf-8', mb_detect_encoding($html));
        $dompdf->loadHtml($html, 'UTF-8');
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $output = $dompdf->output();

        $generateNamePdf = "upload/Temp.pdf";
        file_put_contents($generateNamePdf, $output);
        header("Content-type:application/pdf");
        header("Content-Disposition:attachment;filename=downloaded.pdf");
        readfile($generateNamePdf);
    }


    public function actionDelete()
    {
        $id = App::call()->request->getParams()['id'];
        $trigger = App::call()->offerStatusTriggerRepository->getObject($id);
        $trigger->deleted = '1';
        App::call()->offerStatusTriggerRepository->save($trigger);
    }

}


