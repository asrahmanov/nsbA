<?php

namespace app\controllers;

use app\engine\App;
use app\models\entities\BiospecimenType;
use app\models\entities\Sites;
use app\models\entities\CompanyTypes;
use app\models\entities\CompaniesContacts;
use app\models\entities\Disease;
use app\models\entities\DiseaseGroup;
use app\models\entities\DiseaseCategory;

use app\models\entities\StorageConditions;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

class SitesController extends Controller
{

    protected $layout = 'main';
    protected $folder = 'sites/';
    protected $defaultAction = 'sites';

    public function actionSites()
    {
        $role_id = App::call()->session->getSession('role_id');
        echo $this->render($this->folder . $this->defaultAction, [
            'role' => $role_id
        ]);
    }

    public function actionStatuses()
    {
        $role_id = App::call()->session->getSession('role_id');
        echo $this->render($this->folder . 'statuses');
    }

    public function actionGetSitesAll()
    {
        $role_id = App::call()->session->getSession('role_id');
        $user_id = App::call()->session->getSession('user_id');

        // Если роль Менеджео , то показываем только его сайты
        if ($role_id == 3) {
            // Отдельный менеджер который является рукводотелем ЛПО
            // ему надо видеть все сайты 32 id
            if ($user_id == 32) {
                $fr_table = App::call()->managerSitesRepository->getSiteManagerbyAll();
            } else {
                $fr_table = App::call()->managerSitesRepository->getSiteManagerbyUser($user_id);

            }
        } elseif ($role_id == 8) {
            $fr_table = App::call()->managerSitesRepository->getSiteManagerbyUser($user_id);
        } else {
            $fr_table = App::call()->sitesRepository->getSeitesAll();
        }
        $lab_levels = App::call()->labLevelsRepository->getAll();
        $labLevelsById = new \stdClass();
        foreach ($lab_levels as $lab_level)
            $labLevelsById->{$lab_level['id']} = $lab_level['level_name'];
        $json['data'] = [];
        foreach ($fr_table as $fr_row) {
            $fr_row['managers'] = App::call()->managerSitesRepository->getSiteManagerbySites($fr_row['site_id']);
            $fr_row['lab_level'] = $labLevelsById->{$fr_row['level_id']};
            $json['data'][] = $fr_row;
        }

        echo json_encode($json);
    }

    public function actionInfo()
    {
        if (isset(App::call()->request->getParams()['site'])) {
            $id = App::call()->request->getParams()['site'];
            $role_id = App::call()->session->getSession('role_id');
            $user_id = App::call()->session->getSession('user_id');
            $siteManger = App::call()->managerSitesRepository->getSiteManagerbySites($id);
            $check = false;
            if ($role_id == 3) {
                for ($i = 0; $i < count($siteManger); $i++) {
                    if ($siteManger[$i]['user_id'] === $user_id) {
                        $check = true;
                    }
                }
                if (!$check) {
                    return $this->render('404', $params = []);
                }
            }
            $sites = App::call()->sitesRepository->getOne($id);
            $cities = App::call()->сitiesRepository->getAllOrder();
            $users = App::call()->usersRepository->getWhereOrderOnlyManager();
            $department_id = App::call()->session->getSession('department_id');
            $siteType = App::call()->siteTypesRepository->getAll();
            $siteLog = App::call()->siteLogWorkStatusesRepository->getWhere([
                'site_id' => $id
            ]);
            foreach ($siteLog as $key => $item) {
                $datetime_frags = explode(' ', $item['date_change']);
                $date_frags = explode('-', $datetime_frags[0]);
                $russian_date = "{$date_frags[2]}.{$date_frags[1]},{$date_frags[0]} {$datetime_frags[1]}";
                $siteLog[$key]['date_change'] = $russian_date;
                $siteLog[$key]['work_status'] =
                    $item['work_status'] === '1' ? 'Работает' :
                        ($item['work_status'] === '2' ? 'Частично работает' :
                            ($item['work_status'] === '3' ? 'Не работает' : ''));
                $logged_user = App::call()->usersRepository->getOne($item['user_id']);
                $siteLog[$key]['user_fullname'] = "{$logged_user['lasttname']} {$logged_user['firstname']} {$logged_user['patronymic']}";
            }
            $labLevels = App::call()->labLevelsRepository->getAll();
            $siteLabLevel = App::call()->labLevelsRepository->getOne($sites['level_id']);
            $user_id = App::call()->session->getSession('user_id');
            echo $this->render('sites/info', [
                'sites' => $sites,
                'siteType' => $siteType,
                'сities' => $cities,
                'users' => $users,
                'role' => $role_id,
                'department_id' => $department_id,
                'log' => $siteLog,
                'lab_levels' => $labLevels,
                'site_lab_level' => $siteLabLevel,
                'user_id' => $user_id
            ]);
        } else {
            echo $this->render('404');
        }
    }


    public function actionGenerateContact()
    {

        if (isset(App::call()->request->getParams()['site'])) {

            $id = App::call()->request->getParams()['site'];

            $contacts = App::call()->companiesContactsRepository->getSiteContactsBySiteId($id);

            $role_id = App::call()->session->getSession('role_id');
            $user_id = App::call()->session->getSession('user_id');

            $siteManger = App::call()->managerSitesRepository->getSiteManagerbySites($id);


            $check = false;
            if ($role_id == 3) {
                for ($i = 0; $i < count($siteManger); $i++) {
                    if ($siteManger[$i]['user_id'] != $user_id) {
                        $check = false;
                    } else {
                        $check = true;
                    }
                }
                if (!$check) {

                    return $this->render('404', $params = []);
                }

            }

            $sites = App::call()->sitesRepository->getOne($id);
            $cities = App::call()->сitiesRepository->getAllOrder();
            $users = App::call()->usersRepository->getWhereOrderOnlyManager();
            $department_id = App::call()->session->getSession('department_id');
            $siteType = App::call()->siteTypesRepository->getAll();
            $siteLog = App::call()->siteLogWorkStatusesRepository->getWhere([
                'site_id' => $id
            ]);

            foreach ($siteLog as $key => $item) {
                $datetime_frags = explode(' ', $item['date_change']);
                $date_frags = explode('-', $datetime_frags[0]);
                $russian_date = "{$date_frags[2]}.{$date_frags[1]},{$date_frags[0]} {$datetime_frags[1]}";
                $siteLog[$key]['date_change'] = $russian_date;
                $siteLog[$key]['work_status'] =
                    $item['work_status'] === '1' ? 'Работает' :
                        ($item['work_status'] === '2' ? 'Частично работает' :
                            ($item['work_status'] === '3' ? 'Не работает' : ''));
                $logged_user = App::call()->usersRepository->getOne($item['user_id']);
                $siteLog[$key]['user_fullname'] = "{$logged_user['lasttname']} {$logged_user['firstname']} {$logged_user['patronymic']}";
            }

            $labLevels = App::call()->labLevelsRepository->getAll();
            $siteLabLevel = App::call()->labLevelsRepository->getOne($sites['level_id']);
            $user_id = App::call()->session->getSession('user_id');

            $content = " <table  border='1'>
                                        <th>Фамилия</th>
                                        <th>Имя</th>
                                        <th>Отчество</th>
                                        <th>Группа контакта</th>
                                        <th>Должность</th>
                                        <th>Тип контакта</th>
                                        <th>Специализация</th>
                                        <th>Рабочий телефон</th>
                                        <th>Мобильный телефон</th>
                                        <th>Рабочий e-mail</th>
                                        <th>Рабочий e-mail</th>
                                        <th>Частный e-mail</th>
                                        <th>Адрес</th>
                                        <th>Комментарий</th>
                                        <th>Нозологии</th>
                                        <th>Действующий контакт</th>
                                        <th>ГПХ</th>
                                        <th>Участвуют в рассылке</th>
                                        <th>Квотируемый</th>
                                    </thead>
                                    <tbody>";
            foreach ($contacts as $contact) {
                $content .= "<tr>
                            <td>{$contact['firstname']}</td>
                            <td>{$contact['lastname']}</td>
                            <td>{$contact['patronymic']}</td>
                            <td>{$contact['contact_type']}</td>
                            <td>{$contact['job']}</td>
                            <td>{$contact['contact_source']}</td>
                            <td>{$contact['work_phone']}</td>
                            <td>{$contact['home_phone']}</td>
                            <td>{$contact['other_phone']}</td>
                            <td>{$contact['work_email']}</td>
                            <td>{$contact['home_email']}</td>
                            <td>{$contact['address']}</td>
                            <td>{$contact['comment']}</td>
                            <td>{$contact['nosology']}</td>
                            <td>{$contact['active']}</td>
                            <td>{$contact['civil_contract']}</td>
                            <td>{$contact['newsletter']}</td>
                            <td>{$contact['special_id']}</td>
                            <td>{$contact['quotable']}</td>
                            </tr>
                          
                            ";
            }

            $content .= " <tbody></table>";

//            $this->layout = 'exel';
//            echo $this->render('sites/generateSiteContacts', [
//                'sites' => $sites,
//                'siteType' => $siteType,
//                'сities' => $cities,
//                'users' => $users,
//                'role' => $role_id,
//                'department_id' => $department_id,
//                'log' => $siteLog,
//                'lab_levels' => $labLevels,
//                'site_lab_level' => $siteLabLevel,
//                'user_id' => $user_id,
//                'contacts' => $contacts
//            ]);

            $filename = "{$sites['site_name']}.xls";
            header('Content-type: application/ms-excel');
            header('Content-Disposition: attachment; filename=' . $filename);
            echo $content;


        } else {
            echo $this->render('404');
        }
    }

//    public function actionGenerateCSVContact()
//    {
//        if (isset(App::call()->request->getParams()['site'])) {
//            $id = App::call()->request->getParams()['site'];
//            $contacts = App::call()->companiesContactsRepository->getSiteContactsBySiteId($id);
//            $role_id = App::call()->session->getSession('role_id');
//            $user_id = App::call()->session->getSession('user_id');
//            $siteManger = App::call()->managerSitesRepository->getSiteManagerbySites($id);
//            $check = false;
//            if ($role_id == 3) {
//                for ($i = 0; $i < count($siteManger); $i++) {
//                    if ($siteManger[$i]['user_id'] != $user_id) {
//                        $check = false;
//                    } else {
//                        $check = true;
//                    }
//                }
//                if (!$check) {
//                    return $this->render('404', $params = []);
//                }
//            }
//            $sites = App::call()->sitesRepository->getOne($id);
//            $content = "First Name,Middle Name,Last Name,Title,Suffix,Nickname,Given Yomi,Surname Yomi,E-mail Address,E-mail 2 Address,E-mail 3 Address,Home Phone,Home Phone 2,Business Phone,Business Phone 2,Mobile Phone,Car Phone,Other Phone,Primary Phone,Pager,Business Fax,Home Fax,Other Fax,Company Main Phone,Callback,Radio Phone,Telex,TTY/TDD Phone,IMAddress,Job Title,Department,Company,Office Location,Manager's Name,Assistant's Name,Assistant's Phone,Company Yomi,Business Street,Business City,Business State,Business Postal Code,Business Country/Region,Home Street,Home City,Home State,Home Postal Code,Home Country/Region,Other Street,Other City,Other State,Other Postal Code,Other Country/Region,Personal Web Page,Spouse,Schools,Hobby,Location,Web Page,Birthday,Anniversary,Notes" . PHP_EOL;
//            foreach ($contacts as $contact) {
//                $content .= "{$contact['firstname']},{$contact['patronymic']},{$contact['lastname']},,,,,,{$contact['work_email']},{$contact['home_email']},,,,{$contact['work_phone']},,{$contact['home_phone']},,{$contact['other_phone']},,,,,,,,,,,,{$contact['job']},,,,,,,,,,,,,,,,,,,,,,,,,,,{$contact['address']},,,,{$contact['comment']}" . PHP_EOL;
//            }
//            $filename = "{$sites['site_name']}.csv";
//            header('Content-type: text/csv');
//            header('Content-Disposition: attachment; filename=' . $filename);
//            echo $content;
//        } else {
//            echo $this->render('404');
//        }
//    }

    public function actionGenerateVCFContact()
    {
        if (isset(App::call()->request->getParams()['site'])) {
            $id = App::call()->request->getParams()['site'];
            $contacts = App::call()->companiesContactsRepository->getSiteContactsBySiteId($id);
            $role_id = App::call()->session->getSession('role_id');
            $user_id = App::call()->session->getSession('user_id');
            $siteManger = App::call()->managerSitesRepository->getSiteManagerbySites($id);
            $check = false;
            if ($role_id == 3) {
                for ($i = 0; $i < count($siteManger); $i++) {
                    if ($siteManger[$i]['user_id'] != $user_id) {
                        $check = false;
                    } else {
                        $check = true;
                    }
                }
                if (!$check) {
                    return $this->render('404', $params = []);
                }
            }
            $sites = App::call()->sitesRepository->getOne($id);
            $content = '';
            foreach ($contacts as $contact) {
                // {$contact['home_phone']}
                $content .=
                "BEGIN:VCARD

VERSION:3.0

FN:г-н {$contact['firstname']} {$contact['patronymic']} {$contact['lastname']}

N:{$contact['firstname']} {$contact['patronymic']} {$contact['lastname']};г-н;

EMAIL;TYPE=INTERNET;TYPE=HOME:{$contact['home_email']}

EMAIL;TYPE=INTERNET;TYPE=WORK:{$contact['work_email']}

TEL;TYPE=CELL:{$contact['work_phone']}

TEL;TYPE=HOME:{$contact['other_phone']}

TITLE:{$contact['job']}

NOTE:{$contact['comment']}

END:VCARD";
            }
            $filename = "{$sites['site_name']}.vcf";
            header('Content-type: x-vcard');
            header('Content-Type: text/html; charset=cp1251');
            header('Content-Disposition: attachment; filename=' . $filename);
            echo $content;
        } else {
            echo $this->render('404');
        }
    }

    public function actionGenerateVCFContactOne()
    {
        if (isset(App::call()->request->getParams()['id'])) {
            $id = App::call()->request->getParams()['id'];
            $contact = App::call()->companiesContactsRepository->getOne($id);
            $role_id = App::call()->session->getSession('role_id');
            $user_id = App::call()->session->getSession('user_id');
            $siteManger = App::call()->managerSitesRepository->getSiteManagerbySites($id);
            $check = false;
            if ($role_id == 3) {
                for ($i = 0; $i < count($siteManger); $i++) {
                    if ($siteManger[$i]['user_id'] != $user_id) {
                        $check = false;
                    } else {
                        $check = true;
                    }
                }
                if (!$check) {
                    return $this->render('404', $params = []);
                }
            }
            $sites = App::call()->sitesRepository->getOne($id);
            $content =
            "BEGIN:VCARD

VERSION:3.0

FN:г-н {$contact['firstname']} {$contact['patronymic']} {$contact['lastname']}

N:{$contact['firstname']} {$contact['patronymic']} {$contact['lastname']};г-н;

EMAIL;TYPE=INTERNET;TYPE=HOME:{$contact['home_email']}

EMAIL;TYPE=INTERNET;TYPE=WORK:{$contact['work_email']}

TEL;TYPE=CELL:{$contact['work_phone']}

TEL;TYPE=HOME:{$contact['other_phone']}

TITLE:{$contact['job']}

NOTE:{$contact['comment']}

END:VCARD";
            $filename = "{$contact['firstname']} {$contact['patronymic']} {$contact['lastname']}.vcf";
            header('Content-type: x-vcard');
            header('Content-Type: text/html; charset=cp1251');
            header('Content-Disposition: attachment; filename=' . $filename);
            echo $content;
        } else {
            echo $this->render('404');
        }
    }


    public function actionAdd()
    {
        $сities = App::call()->сitiesRepository->getAllOrder();
        $users = App::call()->usersRepository->getWhereOrderOnlyManager();
        $siteType = App::call()->siteTypesRepository->getAll();
        $role_id = App::call()->session->getSession('role_id');
        $lab_levels = App::call()->labLevelsRepository->getAll();

        echo $this->render('sites/add', [
            'siteType' => $siteType,
            'сities' => $сities,
            'users' => $users,
            'role' => $role_id,
            'lab_levels' => $lab_levels
        ]);
    }


    public function actionSave()
    {
        if (isset(App::call()->request->getParams()['site_id'])) {
            $site_id = App::call()->request->getParams()['site_id'];
            $site = App::call()->sitesRepository->getObject($site_id);
        } else {
            $site = new Sites();
        }

        if (isset(App::call()->request->getParams()['site_code'])) {
            $site_code = App::call()->request->getParams()['site_code'];
            $site->site_code = $site_code;
        }

        if (isset(App::call()->request->getParams()['site_name'])) {
            $site_name = App::call()->request->getParams()['site_name'];
            $site->site_name = $site_name;
        }

        if (isset(App::call()->request->getParams()['site_type'])) {
            $site_type = App::call()->request->getParams()['site_type'];
            $site->site_type = $site_type;
        }

        if (isset(App::call()->request->getParams()['city'])) {
            $city = App::call()->request->getParams()['city'];
            $site->city = $city;
        }

        if (isset(App::call()->request->getParams()['contract'])) {
            $contract = App::call()->request->getParams()['contract'];
            $site->contract = $contract;
        }

        if (isset(App::call()->request->getParams()['term_validity'])) {
            $term_validity = App::call()->request->getParams()['term_validity'];
            $site->term_validity = $term_validity;
        }

        if (isset(App::call()->request->getParams()['approved'])) {
            $approved = App::call()->request->getParams()['approved'];
            $site->approved = $approved;
        }

        if (isset(App::call()->request->getParams()['site_status'])) {
            $site_status = App::call()->request->getParams()['site_status'];
            $site->site_status = $site_status;
        }

        if (isset(App::call()->request->getParams()['irb_approval'])) {
            $irb_approval = App::call()->request->getParams()['irb_approval'];
            $site->irb_approval = $irb_approval;
        }

        if (isset(App::call()->request->getParams()['processing'])) {
            $processing = App::call()->request->getParams()['processing'];
            $site->processing = $processing;
        }


        if (isset(App::call()->request->getParams()['level'])) {
            $level = App::call()->request->getParams()['level'];
            $site->level = $level;
        }

        if (isset(App::call()->request->getParams()['misc'])) {
            $misc = App::call()->request->getParams()['misc'];
            $site->misc = $misc;
        }
        if (isset(App::call()->request->getParams()['addr'])) {
            $addr = App::call()->request->getParams()['addr'];
            $site->addr = $addr;
        }
        if (isset(App::call()->request->getParams()['company_name'])) {
            $company_name = App::call()->request->getParams()['company_name'];
            $site->company_name = $company_name;
        }
        if (isset(App::call()->request->getParams()['employee_quant'])) {
            $employee_quant = App::call()->request->getParams()['employee_quant'];
            $site->employee_quant = $employee_quant;
        }
        if (isset(App::call()->request->getParams()['phone'])) {
            $phone = App::call()->request->getParams()['phone'];
            $site->phone = $phone;
        }
        if (isset(App::call()->request->getParams()['site_addr'])) {
            $site_addr = App::call()->request->getParams()['site_addr'];
            $site->site_addr = $site_addr;
        }
        if (isset(App::call()->request->getParams()['email'])) {
            $email = App::call()->request->getParams()['email'];
            $site->email = $email;
        }
        if (isset(App::call()->request->getParams()['text_city'])) {
            $text_city = App::call()->request->getParams()['text_city'];
            $site->text_city = $text_city;
        }
        if (isset(App::call()->request->getParams()['company_type_id'])) {
            $company_type_id = App::call()->request->getParams()['company_type_id'];
            $site->company_type_id = $company_type_id;
        }
        if (isset(App::call()->request->getParams()['level_id'])) {
            $level_id = App::call()->request->getParams()['level_id'];
            $site->level_id = $level_id;
        }
        if (isset(App::call()->request->getParams()['quotable'])) {
            $quotable = App::call()->request->getParams()['quotable'];
            $site->quotable = $quotable;
        }


        if (isset(App::call()->request->getParams()['work_comment_manager'])) {
            $work_comment_manager = App::call()->request->getParams()['work_comment_manager'];
            $site->work_comment_manager = $work_comment_manager;
        }
        if (isset(App::call()->request->getParams()['work_comment_auditor'])) {
            $work_comment_auditor = App::call()->request->getParams()['work_comment_auditor'];
            $site->work_comment_auditor = $work_comment_auditor;
        }
        if (isset(App::call()->request->getParams()['work_status_manager'])) {
            $work_status_manager = App::call()->request->getParams()['work_status_manager'];
            $site->work_status_manager = $work_status_manager;
        }
        if (isset(App::call()->request->getParams()['work_status_auditor'])) {
            $work_status_auditor = App::call()->request->getParams()['work_status_auditor'];
            $site->work_status_auditor = $work_status_auditor;
        }
        if (isset(App::call()->request->getParams()['auditor_confirm'])) {
            $site->auditor_confirm++;
        }
        if (isset(App::call()->request->getParams()['manager_confirm'])) {
            $site->manager_confirm++;
        }


        $user_id = App::call()->session->getSession('user_id');
        $site->trigger_user_id = $user_id;


        $result = App::call()->sitesRepository->save($site);


        if ($result > 0) {
            $site_new = App::call()->sitesRepository->getObject($result);
            $site_new->site_code = $result - 1;
            App::call()->sitesRepository->save($site_new);
        }

        echo json_encode(['result' => $result]);

    }


    public function actionGetTypes()
    {
        $result = App::call()->companyTypesRepository->getAll();
        echo json_encode(['result' => $result]);
    }

    public function actionGetCompanyTypes()
    {
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
        $reader->setReadDataOnly(TRUE);
        $spreadsheet = $reader->load("COMPANY_need.xlsx");

        $dataArray = $spreadsheet->getActiveSheet()
            ->rangeToArray(
                'B1:CG1054',     // The worksheet range that we want to retrieve
                '',        // Value that should be returned for empty cells
                false,        // Should formulas be calculated (the equivalent of getCalculatedValue() for each cell)
                false,        // Should values be formatted (the equivalent of getFormattedValue() for each cell)
                true         // Should the array be indexed by cell row and cell column
            );
        $numrows = count($dataArray);

        $company_types = [];
        for ($i = 0; $i < $numrows; $i++) {
            $row = $dataArray[$i];
            if (is_numeric($row['AQ'])) {
                $row_company_type = $row['D'];
                $company_types[] = $row_company_type;
            }
        }
        $company_types = array_unique($company_types);
        sort($company_types);

        foreach ($company_types as $new_company_type_name) {
            $new_company_type_name = $new_company_type_name === '' ? "нет" : $new_company_type_name;
            $new_company_type = new CompanyTypes($new_company_type_name);
            $result = App::call()->companyTypesRepository->save($new_company_type);
        }
    }

    public function actionGetCompaniesData()
    {
        $types = App::call()->companyTypesRepository->getAll();

        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
        $reader->setReadDataOnly(TRUE);
        $spreadsheet = $reader->load("COMPANY_need.xlsx");

        $dataArray = $spreadsheet->getActiveSheet()
            ->rangeToArray(
                'B1:CG1054',     // The worksheet range that we want to retrieve
                '',        // Value that should be returned for empty cells
                false,        // Should formulas be calculated (the equivalent of getCalculatedValue() for each cell)
                false,        // Should values be formatted (the equivalent of getFormattedValue() for each cell)
                true         // Should the array be indexed by cell row and cell column
            );
        $numrows = count($dataArray);

        for ($i = 0; $i < $numrows; $i++) {
            $row = $dataArray[$i];
            if (is_numeric($row['AQ'])) {
                $site_id = $row['AQ'] + 1;
                $addrs[$site_id] = $row['J'];
                for ($j = 1; $dataArray[$i + $j]['AQ'] === '' && $dataArray[$i + $j]['J'] !== ''; $j++) {
                    $nextRow = $dataArray[$i + $j];
                    $addrs[$site_id] .= ' ' . $nextRow['J'];
                }
                $site = App::call()->sitesRepository->getObject($site_id);
                $site->addr = $addrs[$site_id];
                $site->company_name = $row['C'];
                $site->employee_quant = $row['E'];
                $site->phone = $row['F'];
                $site->site_addr = $row['G'];
                $site->email = $row['H'];
                $site->text_city = $row['M'];
                $company_type_id = 1;
                foreach ($types as $type) {
                    if ($row['D'] === $type['type_name']) {
                        $company_type_id = $type['id'];
                    }
                }
                $site->company_type = $company_type_id;
                App::call()->sitesRepository->save($site);
            }
        }
    }

    public function actionGetCompaniesContacts()
    {
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
        $reader->setReadDataOnly(TRUE);
        $spreadsheet = $reader->load("CONTACT_20200813_ebd1a790_5f3529dfd7bb1.xlsx");

        $dataArray = $spreadsheet->getActiveSheet()
            ->rangeToArray(
                'A2:Y1588',     // The worksheet range that we want to retrieve
                '',        // Value that should be returned for empty cells
                false,        // Should formulas be calculated (the equivalent of getCalculatedValue() for each cell)
                false,        // Should values be formatted (the equivalent of getFormattedValue() for each cell)
                true         // Should the array be indexed by cell row and cell column
            );

        foreach ($dataArray as $contact) {
            if (is_numeric($contact['W'])) {
                $new_contact = new CompaniesContacts(
                    $contact['W'] + 1,
                    $contact['B'],
                    $contact['C'],
                    $contact['D'],
                    $contact['V'] === 'Врачи' ? 'doctor' : 'administer',
                    $contact['E'],
                    $contact['G'],
                    $contact['I'],
                    $contact['J'],
                    $contact['K'],
                    $contact['M'],
                    $contact['N'],
                    $contact['O'],
                    $contact['S'],
                    $contact['T'],
                    $contact['U'] === 'да' ? 1 : 0,
                    $contact['X'] === 'да' ? 1 : 0,
                    $contact['Y'] === 'да' ? 1 : 0
                );

                App::call()->companiesContactsRepository->save($new_contact);
            }
        }
    }

    public function actionGetDiseases1()
    {
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
        $reader->setReadDataOnly(TRUE);
        $spreadsheet = $reader->load("Diagnosis for BD.xlsx");

        $dataArray = $spreadsheet->getActiveSheet()
            ->rangeToArray(
                'B2:F365',     // The worksheet range that we want to retrieve
                '',        // Value that should be returned for empty cells
                false,        // Should formulas be calculated (the equivalent of getCalculatedValue() for each cell)
                false,        // Should values be formatted (the equivalent of getFormattedValue() for each cell)
                true         // Should the array be indexed by cell row and cell column
            );

        $disease_categories = [];
        $diseases = new \stdClass();
        foreach ($dataArray as $disease_row) {
            $disease_category = $disease_row['F'];
            if (!property_exists($diseases, $disease_category)) {
                $diseases->{$disease_category} = new \stdClass();
                $new_cat = new DiseaseCategory($disease_category);
                $cat_id = App::call()->diseaseCategoryRepository->save($new_cat);
                $diseases->{$disease_category}->category_id = $cat_id;
            } else {
                $cat_id = $diseases->{$disease_category}->category_id;
            }
            $disease_group = $disease_row['E'];
            if (!property_exists($diseases->{$disease_category}, $disease_group)) {
                $diseases->{$disease_category}->{$disease_group} = new \stdClass();
                $new_group = new DiseaseGroup($cat_id, $disease_group);
                $group_id = App::call()->diseaseGroupRepository->save($new_group);
                $diseases->{$disease_category}->{$disease_group}->group_id = $group_id;
            } else {
                $group_id = $diseases->{$disease_category}->{$disease_group}->group_id;
            }
            $disease_name = $disease_row['D'];
            $disease_name_russian = $disease_row['C'];
            $disease_name_russian_old = $disease_row['B'];
            $new_disease = new Disease($group_id, $disease_name, $disease_name_russian, $disease_name_russian_old);
            App::call()->diseaseRepository->save($new_disease);
        }
//        echo '<pre>';
//        var_dump($diseases);
//        echo '</pre>';
    }

    public function actionGetDiseases2()
    {
        $disease_categories = App::call()->diseaseCategoryRepository->getAll();
        $disease_categories_by_id = new \stdClass();
        foreach ($disease_categories as $disease_category) {
            $disease_categories_by_id->{$disease_category['id']} = $disease_category['category_name'];
        }
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
        $reader->setReadDataOnly(TRUE);
        $spreadsheet = $reader->load("Diagnosis for BD.xlsx");

        $dataArray = $spreadsheet->getActiveSheet()
            ->rangeToArray(
                'E2:E365',     // The worksheet range that we want to retrieve
                '',        // Value that should be returned for empty cells
                false,        // Should formulas be calculated (the equivalent of getCalculatedValue() for each cell)
                false,        // Should values be formatted (the equivalent of getFormattedValue() for each cell)
                true         // Should the array be indexed by cell row and cell column
            );
        foreach ($dataArray as $disease_row) {
            $disease_category = $disease_row['E'];
            if (array_search($disease_category, $disease_categories) === false) {
                $disease_categories[] = $disease_category;
                $new_cat = new DiseaseCategory($disease_category);
                App::call()->diseaseCategoryRepository->save($new_cat);
            }
        }
    }

    public function actionGetBio()
    {
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
        $reader->setReadDataOnly(TRUE);
        $spreadsheet = $reader->load("Library.xlsx");

        $dataArray = $spreadsheet->getActiveSheet()
            ->rangeToArray(
                'D4:F117',     // The worksheet range that we want to retrieve
                '',        // Value that should be returned for empty cells
                false,        // Should formulas be calculated (the equivalent of getCalculatedValue() for each cell)
                false,        // Should values be formatted (the equivalent of getFormattedValue() for each cell)
                true         // Should the array be indexed by cell row and cell column
            );
        $biotypes = [];
        $conds = [];
        foreach ($dataArray as $disease_row) {
            $bio = $disease_row['D'];
            if (array_search($bio, $biotypes) === false) {
                $biotypes[] = $bio;
                $new_bio = new BiospecimenType($bio);
                App::call()->biospecimenTypeRepository->save($new_bio);
            }
            $cond = $disease_row['F'];
            if (array_search($cond, $conds) === false) {
                $conds[] = $cond;
                $new_cond = new StorageConditions($cond);
                App::call()->storageConditionsRepository->save($new_cond);
            }
        }
//        echo '<pre>';
//        var_dump($biotypes);
//        echo '</pre>';
    }

    public function actionGetOptions () {
        $sites = App::call()->sitesRepository->getOptions();
        echo json_encode($sites);
    }

}
