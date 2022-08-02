<?php

namespace app\controllers;

use app\engine\App;
use app\models\entities\ClinicalCase;

use app\models\entities\Files;
use app\models\entities\Mail;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ClinicalCaseController extends Controller
{

    protected $layout = 'main';
    protected $folder = 'ClinicalCase/';
    protected $defaultAction = 'ClinicalCase';

    public function actionClinicalCase()
    {
        $role_id = App::call()->session->getSession('role_id');
        $dateOne = DATE('Y-m-d');
//        $dateAt = strtotime('-3 MONTH', strtotime($dateOne));
        $dateTwo = '2021-08-01';
        echo $this->render($this->folder . $this->defaultAction, [
            'role' => $role_id,
            'dateOne' => $dateOne,
            'dateTwo' => $dateTwo,
            'user_id' => App::call()->session->getSession('user_id')
        ]);
    }

    public function actionTable()
    {
        $dateOne = App::call()->request->getParams()['dateOne'];
        $dateTwo = App::call()->request->getParams()['dateTwo'];
        $sites_all = App::call()->sitesRepository->getAll();

        $siteNamesById = new \stdClass();
        foreach ($sites_all as $site) {
            if ($site['site_code'] !== null)
                $siteNamesById->{$site['site_code']} = $site['site_name'];
        }
        $managers = App::call()->usersRepository->getAll();
        $managerNamesByid = new \stdClass();
        foreach ($managers as $manager)
            $managerNamesByid->{$manager['id']} = "{$manager['lasttname']} {$manager['firstname']} {$manager['patronymic']}";
        $clinical_cases = App::call()->clinicalCaseRepository->getAllbyDateDesc($dateTwo, $dateOne);
        $json['data'] = [];
        for ($i = 0; $i < count($clinical_cases); $i++) {
            $clinical_cases[$i]['comment'] = $clinical_cases[$i]['comment'] ?: '';
            $clinical_cases[$i]['clinical_date'] = $clinical_cases[$i]['clinical_date'] ?: '';
            if (property_exists($siteNamesById, $clinical_cases[$i]['site_id']))
                $clinical_cases[$i]['site_name'] = $siteNamesById->{$clinical_cases[$i]['site_id']};
            else
                $clinical_cases[$i]['site_name'] = '';
            $json['data'][$i] = $clinical_cases[$i];
        }
        echo json_encode($json);
    }

    public function actionUpload()
    {
        $dirname = 'upload/ClinicalCaseXLS/';
        $file_tmp = $_FILES['file']['tmp_name'];
        $file_name = $_FILES['file']['name'];
        $file_destination = $dirname . $file_name;

        if (!file_exists($dirname))
            mkdir($dirname);

        if (move_uploaded_file($file_tmp, $file_destination)) {
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
            $reader->setReadDataOnly(TRUE);
            $spreadsheet = $reader->load($file_destination);
            $num_rows = $spreadsheet->getActiveSheet()->getHighestRow();
            $dataArray = $spreadsheet->getActiveSheet()
                ->rangeToArray(
                    "A1:G$num_rows",     // The worksheet range that we want to retrieve
                    '',        // Value that should be returned for empty cells
                    false,        // Should formulas be calculated (the equivalent of getCalculatedValue() for each cell)
                    false,        // Should values be formatted (the equivalent of getFormattedValue() for each cell)
                    true         // Should the array be indexed by cell row and cell column
                );

            $managers = App::call()->usersRepository->getAll();
            $managerIdsByProjectName = new \stdClass();
            foreach ($managers as $manager) {
                if (array_search($manager['project_name'], ['', null]) === false) {
                    $managerIdsByProjectName->{$manager['project_name']} = $manager['id'];
                }
            }
            $test = false;
            foreach ($dataArray as $row) {


                $type = gettype($row['G']);

                if($type == 'integer') {
                    $excel_date = $row['G']; //here is that value 41621 or 41631
                    $unix_date = ($excel_date - 25569) * 86400;
                    $date = gmdate("Y-m-d", $unix_date);
                } else {
                        if($row['G'] == '' or $row['G'] == '0000-00-00'){
                            $row['G'] = '1970-01-01';
                        }

                    $date = $row['G'];
                }




                $time = $row['G'];

                if (!$test) {
                    if ($row['A'] === 'code_inside' AND $row['B'] === 'site_id' AND $row['C'] === 'site_manager' AND $row['D'] === 'case_id_updated') {
                        $test = true;
                    } else {
                        die('Неправильный формат файла!!!');
                    }
                } else {
                    if ($row['D'] !== '') {
                        if ($row['C'] === 'Анастасия Жаберева' || $row['C'] === 'Кириченко Анастасия Сергеевна' || $row['C'] === 'Анастасия Жаберева')
                            $row['C'] = 'Анастасия Кириченко';
                        else if ($row['C'] === 'Шарафутдинова Юлия Ильдаровна')
                            $row['C'] = 'Шарафутдинова Юлия';
                        else if ($row['C'] === 'Меньщикова Ирина Феликсовна' || $row['C'] === 'Ирина Меньщикова')
                            $row['C'] = 'Ирина Менщикова';
                        else if ($row['C'] === 'Плотников А.Н.')
                            $row['C'] = 'Антон Плотников';
                        if (property_exists($managerIdsByProjectName, $row['C']))
                            $manager_id = $managerIdsByProjectName->{$row['C']};
                        else
                            $manager_id = 59;

                        $new_clinical_case = new ClinicalCase();
                        $new_clinical_case->project_id = $row['A'];
                        $new_clinical_case->site_id = $row['B'];
                        $new_clinical_case->manager_id = $manager_id;
                        $new_clinical_case->clinical_case_id = $row['D'];
                        $new_clinical_case->site_index = $row['E'];
                        $new_clinical_case->clinical_diagnosis = $row['F'];
                        $new_clinical_case->clinical_date = $date;

                       $res = App::call()->clinicalCaseRepository->save($new_clinical_case);



                    }
                }
            }
            die('Загрузка завершена');
        }

    }

    public function actionSave()
    {
        if (isset(App::call()->request->getParams()['id'])) {
            $id = App::call()->request->getParams()['id'];
            $site = App::call()->clinicalCaseRepository->getObject($id);

        } else {
            $site = New ClinicalCase();
        }

        if (isset(App::call()->request->getParams()['project_id'])) {
            $project_id = App::call()->request->getParams()['project_id'];
            $site->project_id = $project_id;
        }
        if (isset(App::call()->request->getParams()['site_id'])) {
            $site_id = App::call()->request->getParams()['site_id'];
            $site->site_id = $site_id;
        }
        if (isset(App::call()->request->getParams()['manager_id'])) {
            $manager_id = App::call()->request->getParams()['manager_id'];
            $site->manager_id = $manager_id;
        }
        if (isset(App::call()->request->getParams()['clinical_case_id'])) {
            $clinical_case_id = App::call()->request->getParams()['clinical_case_id'];
            $site->clinical_case_id = $clinical_case_id;
        }
        if (isset(App::call()->request->getParams()['site_index'])) {
            $site_index = App::call()->request->getParams()['site_index'];
            $site->site_index = $site_index;
        }
        if (isset(App::call()->request->getParams()['clinical_diagnosis'])) {
            $clinical_diagnosis = App::call()->request->getParams()['clinical_diagnosis'];
            $site->clinical_diagnosis = $clinical_diagnosis;
        }
        if (isset(App::call()->request->getParams()['checked'])) {
            $checked = App::call()->request->getParams()['checked'];
            $site->checked = $checked;
        }
        if (isset(App::call()->request->getParams()['paid'])) {
            $paid = App::call()->request->getParams()['paid'];
            $site->paid = $paid;
        }
        if (isset(App::call()->request->getParams()['form_valid'])) {
            $form_valid = App::call()->request->getParams()['form_valid'];
            $site->form_valid = $form_valid;
        }
        if (isset(App::call()->request->getParams()['comment'])) {
            $comment = App::call()->request->getParams()['comment'];
            $site->comment = $comment;
        }
        if (isset(App::call()->request->getParams()['clinical_date'])) {
            $clinical_date = App::call()->request->getParams()['clinical_date'];
            $site->clinical_date = $clinical_date;
        }
        if (isset(App::call()->request->getParams()['send_count'])) {
            $send_count = App::call()->request->getParams()['send_count'];
            $site->send_count = $send_count;
        }
        if (isset(App::call()->request->getParams()['not_on_mailing_list'])) {
            $not_on_mailing_list = App::call()->request->getParams()['not_on_mailing_list'];
            $site->not_on_mailing_list = $not_on_mailing_list;
        }

        $result = App::call()->clinicalCaseRepository->save($site);

        echo json_encode(['result' => $result]);

    }



    public function actionSendMail()
    {

        if (isset(App::call()->request->getParams()['id'])) {
            $id = App::call()->request->getParams()['id'];
            $clinicalCase = App::call()->clinicalCaseRepository->getObject($id);

            if (($clinicalCase->site_id !== '') && !is_null($clinicalCase->site_id)) {
                $site_id = $clinicalCase->site_id + 1;
                $site = App::call()->sitesRepository->getObject($site_id);
                $site_name = $site->site_name;
                $site_managers = App::call()->managerSitesRepository->getWhere(['site_id' => $site_id]);
            } else {
                die();
            }

            foreach ($site_managers as $manager) {
                $email = $manager->email;
                $subject = "CRF {$clinicalCase->project_id} {$clinicalCase->clinical_case_id}";
                $text = "Добрый день, {$manager->lasttname} {$manager->firstname}. <br>
                    Просьба  в срочном порядке выслать клиническую информацию по образцу:<br>
                    Project ID: {$clinicalCase->project_id}<br> 
                    Site: {$site_name}<br> 
                    {$clinicalCase->clinical_case_id} <br>
                    дата регистрации: {$clinicalCase->clinical_date}";
                $mailSend = new Mail();
                if (($email !== '') && !is_null($email)) {
                    $mailSend->email = $email;
                    $mailSend->subject = $subject;
                    $mailSend->text_mail = $text;
                    $mailSend->send_time = date('Y-m-d H:i:s');
                    $mailSend->send = 'NO';
                    $mailSend->reply_to = 'crf@nbioservice.com';
                    $clinicalCase->send_count = $clinicalCase->send_count + 1;
                    $clinicalCase->last_send = DATE('Y-m-d H:i:s');
                    App::call()->clinicalCaseRepository->save($clinicalCase);
                    App::call()->mailRepository->save($mailSend);
                    $mailSend->reply_to = $email;
                    $mailSend->email = 'crf@nbioservice.com';
                    App::call()->mailRepository->save($mailSend);
                }
            }
        }
    }


    public function actionSendMailLost()
    {
        $draft = App::call()->clinicalCaseRepository->getLost();
        $managers_all = [];
        for ($i = 0; $i < count($draft); $i++) {
            $clinicalCase = App::call()->clinicalCaseRepository->getObject($draft[$i]['id']);
            if (($clinicalCase->site_id !== '') && !is_null($clinicalCase->site_id)) {
                $site_id = $clinicalCase->site_id + 1;
                $site = App::call()->sitesRepository->getObject($site_id);
                $site_name = $site->site_name;
                $site_managers = App::call()->managerSitesRepository->getSiteManagerbySites($site_id);
                foreach ($site_managers as $manager) {
                    $managers_all[$manager['id_user']][] = [
                        'project_id' => $clinicalCase->project_id,
                        'cid' => $clinicalCase->clinical_case_id,
                        'clinical_date' => $clinicalCase->clinical_date,
                        'site_name' => $site_name
                    ];
                    $clinicalCase->send_count = $clinicalCase->send_count + 1;
                    $clinicalCase->last_send = DATE('Y-m-d H:i:s');
                    App::call()->clinicalCaseRepository->save($clinicalCase);
                }
            }
        }

        foreach ($managers_all as $manager_id => $manager_item) {
            $manager = App::call()->usersRepository->getObject($manager_id);
            $text = "";
            $text .= "Добрый день, {$manager->lasttname} {$manager->firstname}. <br>";
            $text .= "Просьба  в срочном порядке выслать клиническую информацию по:. <br>";
            $subject = "CRF ";
            $email = $manager->email;
            foreach ($manager_item as $item) {
                $project_id = $item['project_id'];
                $cid = $item['cid'];
                $clinical_date = $item['clinical_date'];
                $site_name = $item['site_name'];
                $subject .= $cid . " ";
                $text .= "
                    Project ID: $project_id<br> 
                    Site: $site_name<br> 
                    $cid <br>
                    дата регистрации: $clinical_date
                    <br><br>";
            }
            $mailSend = new Mail();
            if (($email !== '') && !is_null($email)) {
                $subject = mb_strimwidth($subject, 0, 100, "...");
                $mailSend->email = $email;
                $mailSend->subject = $subject;
                $mailSend->text_mail = $text;
                $mailSend->send_time = date('Y-m-d H:i:s');
                $mailSend->send = 'NO';
                $mailSend->reply_to = 'crf@nbioservice.com';
                App::call()->mailRepository->save($mailSend);
                $mailSend->reply_to = $email;
                $mailSend->email = 'crf@nbioservice.com';
                $result = App::call()->mailRepository->save($mailSend);
                echo $result;
            }
        }

    }


    public function actionClinicalCaseManager () {
        $user_id = App::call()->session->getSession('user_id');
        $dateOne = DATE('Y-m-d');
//        $dateAt = strtotime('-3 MONTH', strtotime($dateOne));
        $dateAt = strtotime('2020-11-01');
        $dateTwo = date('Y-m-d', $dateAt);
        echo $this->render($this->folder . 'ClinicalCaseManager', [
            'dateOne' => $dateOne,
            'dateTwo' => $dateTwo,
            'user_id' => $user_id
        ]);
    }

    public function actionMyTable()
    {
        $a = 0;
        $dateOne = App::call()->request->getParams()['dateOne'];
        $dateTwo = App::call()->request->getParams()['dateTwo'];
        $user_id = App::call()->request->getParams()['user_id'];
        $mysites = App::call()->managerSitesRepository->getWhere([
            'user_id' => $user_id,
        ]);
        $mysiteids = new \stdClass();
        foreach ($mysites as $mysite) {
            $mysiteids->{$mysite['site_id']} = true;
        }
        $sites_all = App::call()->sitesRepository->getAll();
        $siteNamesById = new \stdClass();
        foreach ($sites_all as $site) {
            if ($site['site_code'] !== null)
                $siteNamesById->{$site['site_code']} = $site['site_name'];
        }
        $clinical_cases = App::call()->clinicalCaseRepository->getAllbyDateDescNotCheck($dateTwo, $dateOne);
        $json['data'] = [];
        for ($i = 0; $i < count($clinical_cases); $i++) {
            $clinical_case_new = $clinical_cases[$i]['site_id'] + 1;
            if (property_exists($mysiteids, $clinical_case_new)) {
                $clinical_cases[$i]['comment'] = $clinical_cases[$i]['comment'] ?: '';
                $clinical_cases[$i]['clinical_date'] = $clinical_cases[$i]['clinical_date'] ?: '';
                if (property_exists($siteNamesById, $clinical_cases[$i]['site_id']))
                    $clinical_cases[$i]['site_name'] = $siteNamesById->{$clinical_cases[$i]['site_id']};
                else
                    $clinical_cases[$i]['site_name'] = '';
                $json['data'][] = $clinical_cases[$i];
            }
        }
        echo json_encode($json, JSON_UNESCAPED_UNICODE);
    }


    public function actionIsNullfix()
    {
       App::call()->clinicalCaseRepository->isNullfix();
    }

}
