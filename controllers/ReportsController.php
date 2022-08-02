<?php

namespace app\controllers;

use app\engine\App;
use DateTime;

class ReportsController extends Controller
{
    protected $defaultAction = 'index';
    protected $render = 'reports/managerQuotes';


    public function actionIndex()
    {
        echo $this->render($this->render);
    }

    public function actionGetReport()
    {
        if (isset(App::call()->request->getParams()['date_from'])) {
            $date_from = App::call()->request->getParams()['date_from'];
        } else {
            $date_from = '';
        }
        if (isset(App::call()->request->getParams()['date_to'])) {
            $date_to = App::call()->request->getParams()['date_to'];
        } else {
            $date_to = '';
        }
//        var_dump($date_to);
        $nbs_users = App::call()->reportsRepository->getJoinOrders($date_from, $date_to);
        $worksheets = App::call()->reportsRepository->getAll();
        $worksheets_by_user = new \stdClass();
        foreach ($worksheets as $worksheet) {
            if (!property_exists($worksheets_by_user, $worksheet['user_id']))
                $worksheets_by_user->{$worksheet['user_id']} = [];
            $worksheets_by_user->{$worksheet['user_id']}[] = $worksheet;
        }
        $quotes = App::call()->quoteRepository->getAll();
        $quotes_by_user = new \stdClass();
        foreach ($quotes as $quote) {
            if (!property_exists($quotes_by_user, $quote['user_id']))
                $quotes_by_user->{$quote['user_id']} = [];
            $quotes_by_user->{$quote['user_id']}[] = $quote;
        }
        foreach ($nbs_users as $a_key => $nbs_user) {
            $nbs_user_id = $nbs_user['id']; $unquoted_ws = []; $quoted_ws = [];
            $avg_quoted_days = 0; $avg_unquoted_days = 0;
            if (property_exists($worksheets_by_user, $nbs_user_id)) {
                if (count($worksheets_by_user->{$nbs_user_id}) > 0) {
                    foreach ($worksheets_by_user->{$nbs_user_id} as $user_worksheet) {
                        $quoted = false;
                        if (property_exists($quotes_by_user, $nbs_user_id) && count($quotes_by_user->{$nbs_user_id}) > 0) {
                            foreach ($quotes_by_user->{$nbs_user_id} as $quote) {
                                if ($quote['proj_id'] === $user_worksheet['proj_id']) {
                                    $quoted = true;
                                    $user_worksheet['quoted'] = $quote['created_at'];
                                }
                            }
                        }
                        if (!$quoted && $user_worksheet['status_id'] === '3')
                            $unquoted_ws[] = $user_worksheet;
                        else if ($quoted)
                            $quoted_ws[] = $user_worksheet;
                    }
                }
            }
            $total_quoted_ws = count($quoted_ws);
            if ($total_quoted_ws > 0) {
                $total_days = 0;
                foreach ($quoted_ws as $quoted_w) {
                    $created_at = new DateTime($quoted_w['created_at']);
                    $quoted = new DateTime($quoted_w['quoted']);
                    $interval = $quoted->diff($created_at);
                    $total_days += intval($interval->format('%a'));
                }
                $avg_quoted_days = round($total_days / $total_quoted_ws);
            }
            $nbs_users[$a_key]['avg_quoted_days'] = $avg_quoted_days;
            $total_unquoted_ws = count($unquoted_ws);
            if ($total_unquoted_ws > 0) {
                $total_days = 0; $total_days_for_avg_count = 0;
                foreach ($unquoted_ws as $unquoted_w) {
                    if ($unquoted_w['updated_at'] !== null) {
                        $cancelled = new DateTime($unquoted_w['updated_at']);
                        $created_at = new DateTime($unquoted_w['created_at']);
                        $interval = $cancelled->diff($created_at);
                        $total_days += intval($interval->format('%a'));
                        $total_days_for_avg_count++;
                    }
                }
                if ($total_days_for_avg_count > 0)
                    $avg_unquoted_days = round($total_days / $total_days_for_avg_count);
                else
                    $avg_unquoted_days = 0;
            }
            $nbs_users[$a_key]['avg_unquoted_days'] = $avg_unquoted_days;
            $nbs_users[$a_key]['new_per'] = round($nbs_users[$a_key]['new'] * 100 / $nbs_users[$a_key]['vsego'], 2);
            $nbs_users[$a_key]['ok_per'] = round($nbs_users[$a_key]['ok'] * 100 / $nbs_users[$a_key]['vsego'], 2);
            $nbs_users[$a_key]['otkaz_per'] = round($nbs_users[$a_key]['otkaz'] * 100 / $nbs_users[$a_key]['vsego'], 2);
        }
        echo json_encode(['data' => $nbs_users]);
    }

}
