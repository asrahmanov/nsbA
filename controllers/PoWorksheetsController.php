<?php

namespace app\controllers;

use app\engine\App;
use app\models\entities\Poworksheets;
use app\models\entities\Worksheets;


class PoWorksheetsController extends Controller
{

    protected $layout = 'admin';
    protected $folder = 'poworksheets/';
    protected $defaultAction = 'Poworksheets';


    public function actionGenerate()
    {
        $nbs_users = App::call()->usersRepository->getManagerNotPrimat();
        $fr_table = App::call()->ordersRepository->getWhere(['fr_status' => 1]);

        for ($i = 0; $i < count($fr_table); $i++) {
            if ($fr_table[$i]['fr_date'] >= '2019-12-01') {
                for ($j = 0; $j < count($nbs_users); $j++) {
                    $worksheets = new Poworksheets();
                    $worksheets->proj_id = $fr_table[$i]['proj_id'];
                    $worksheets->user_id = $nbs_users[$j]['id'];
                    $worksheets->status_id = 1;
                    $result = App::call()->poworksheetsRepository->save($worksheets);

                    echo "Проект - {$fr_table[$i]['proj_id']} - Пользователь {$nbs_users[$j]['id']} - Результат: $result<br>";

                }
            }

        }

    }

    public function actionGenerateById()
    {
        if (isset(App::call()->request->getParams()['id'])) {

            $nbs_users = App::call()->usersRepository->getManager();
            $proj_id = App::call()->request->getParams()['id'];
            for ($j = 0; $j < count($nbs_users); $j++) {
                $worksheets = new Poworksheets();
                $worksheets->proj_id = $proj_id;
                $worksheets->user_id = $nbs_users[$j]['id'];
                $worksheets->status_id = 1;
                $result = App::call()->poworksheetsRepository->save($worksheets);

                if ($result > 0) {
                    $email = App::call()->usersRepository->getEmail($nbs_users[$j]['id']);
                    $order = App::call()->ordersRepository->GetOrdersOne($proj_id);
                    $subject = "Запрос на квотирование проекта {$order['proj_id']}  {$order['project_name']}";
                    $text = "Добрый день, В I-BIOSPlatform Вам поступил запрос на квотирование проекта
                    <a href='http://crm.i-bios.com/worksheets/quote/?id={$result}'>{$order['internal_id']} </a>
                   
                    {$order['project_name']}
                
                    {$order['feas_russian']}
                
                ";
                    App::call()->mailRepository->sendMail($email, $subject, $text);
                }
            }
        } else {
            echo "Жду параметр";
        }
    }

    public function actionGenerateByIdForManagerId()
    {
        if (isset(App::call()->request->getParams()['id'])) {
            $selected_ids = App::call()->request->getParams()['manager_ids'] ?? [];
            $nbs_users = App::call()->usersRepository->getManagersNotBiobank();
            $proj_id = App::call()->request->getParams()['id'];
            $action = 'send'; // - флаг для mail говорящий что отпраляются файлы проекта

            $order = App::call()->poRepository->GetOrdersOne($proj_id);
            $subject = "Новый проект  {$order['po_number']}";

            $text_po = "";

            $t = "Начинается новый проект  <a href='http://crm.i-bios.com/po/info/?idFR={$proj_id}'>{$order['po_number']} </a>  по квоте  {$order['proj_id']}
            ";

            if (isset(App::call()->request->getParams()['po_text'])) {
                $po_text = App::call()->request->getParams()['po_text'];
                $text_po .= $t;
                $text_po .= $po_text;
            }

            if ($order['email_send'] == 1) {

            } else {
                $date = Date('Y-m-d H:i:s');
                $po = App::call()->poRepository->getObject($order['id']);
                $email_lab = App::call()->request->getParams()['lab'];
                App::call()->mailRepository->sendMail($email_lab, $subject, $text_po, 'send' ,$order['proj_id'], $date);
                App::call()->mailRepository->sendMail('Finance@nbioservice.com', $subject, $text_po, 'send' ,$order['proj_id'], $date);
                App::call()->mailRepository->sendMail('ProCor@nbioservice.com', $subject, $text_po, 'send' ,$order['proj_id'], $date);


                $subject_boss = "Распределение квот по проекту {$order['po_number']} по квоте {$order['proj_id']}";
                // Отпр авка письма боссу через 24 часа после отпавки писем менджерам
                $text_boss = "Вам необходимо распределить квоты для сбора образцов по проекту
                <a href='http://crm.i-bios.com/po/info/?idFR={$proj_id}'>Проект № {$order['po_number']} </a>
                ";

                $send_time =  date( "Y-m-d H:i:s", strtotime( $date. "+1 day" ) );
                App::call()->mailRepository->sendMail('Ops@nbioservice.com', $subject_boss, $text_boss, 'no', 0 , $send_time);

                $po->email_send = 1;
                App::call()->poRepository->save($po);
                echo "Отправлено";

            }


            for ($j = 0; $j < count($nbs_users); $j++) {
                if (count($selected_ids) > 0 && array_search($nbs_users[$j]['user_id'], $selected_ids) !== false || count($selected_ids) === 0) {
                    $worksheets = new Poworksheets();
                    $worksheets->proj_id = $proj_id;
                    $worksheets->user_id = $nbs_users[$j]['user_id'];
                    $worksheets->status_id = 1;
                    $result = App::call()->poworksheetsRepository->save($worksheets);

                    $text = "";


                    if (isset(App::call()->request->getParams()['po_text'])) {
                        $po_text = App::call()->request->getParams()['po_text'];
                        $text .= $t;
                        $text .= $po_text;
                    }

                    if ($result > 0) {
                        $email = App::call()->usersRepository->getEmail($nbs_users[$j]['user_id']);
                        App::call()->mailRepository->sendMail($email['email'], $subject, $text, $action, $proj_id);
                        echo $email['email'] . "<br>";

                    }
                }
            }
        } else {
            echo "Жду параметр";
        }
    }


    public function actionGetMy()

    {
        $worksheets = App::call()->poworksheetsRepository->getJoinOrders();
        echo json_encode(['result' => $worksheets]);
    }

    public function actionPoworksheets()
    {
        echo $this->render($this->folder . $this->defaultAction);
    }


    public function actionQuote()
    {

        $id = App::call()->request->getParams()['id'];
        $order = App::call()->poworksheetsRepository->getJoinOrdersByWorksheetsId($id);
        $role = App::call()->session->getSession('role_id');

        $order_diseases = App::call()->orderDiseasesRepository->getWhere(['order_id' => $order[0]['proj_id']]);
        foreach ($order_diseases as $key => $order_disease) {
            $disease = App::call()->diseaseRepository->getOne($order_disease['disease_id']);
            $order_diseases[$key]['disease'] = $disease['disease_name'];
            $order_diseases[$key]['disease_name_russian'] = $disease['disease_name_russian'];
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

        $files = [];
        $nbs_files = App::call()->filesRepository->getFilesbyProjId($order[0]['proj_id']);
        for ($i = 0; $i < count($nbs_files); $i++) {
            $files[] = $nbs_files[$i];
        }
        //var_dump($order[0]['proj_id']);
        $orderDiseases = App::call()->orderDiseasesRepository->getAll();
        $orderDiseasesById = new \stdClass();
        foreach ($orderDiseases as $orderDisease) {
            $orderDiseasesById->{$orderDisease['id']} = $orderDisease;
        }

        $diseases = App::call()->diseaseRepository->getAll();
        $diseasesById = new \stdClass();
        foreach ($diseases as $disease) {
            $diseasesById->{$disease['id']} = $disease;
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

        $quotes = App::call()->quoteRepository->getQuotebyProj($order[0]['proj_id']);
        $quoteIds = [];
        foreach ($quotes as $key => $quote) {
            $quoteIds[] = $quote['id'];
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

        $po_diseasesArray = App::call()->poDiseaseRepository->getWhere(['proj_id' => $order[0]['proj_id']]);
        foreach ($po_diseasesArray as $key => $order_disease) {
            $disease = App::call()->diseaseRepository->getOne($order_disease['disease_id']);
            $po_diseasesArray[$key]['disease'] = $disease['disease_name'];
            $po_diseasesArray[$key]['mutation'] = $disease['disease_name_russian_old'];
        }

        //var_dump($po_diseases);


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


        $diseases_biospecimen_types = App::call()->diseasesBiospecimenTypesRepository->getWhere(['order_id' => $order[0]['proj_id']]);
        foreach ($diseases_biospecimen_types as $key => $diseases_biospecimen_type) {
            $biospecimen_type = $biospecimenTypesById->{$diseases_biospecimen_type['biospecimen_type_id']};
            $diseases_biospecimen_types[$key]['biospecimen_type'] = $biospecimen_type['biospecimen_type'];
            $modification = $sampleModsById->{$diseases_biospecimen_type['mod_id']};
            $diseases_biospecimen_types[$key]['modification'] = $modification['modification'];
        }

        $po_diseases_biospecimen_types = App::call()->poDiseaseSampleModRepository->getWhere(['proj_id' => $order[0]['proj_id']]);
        foreach ($po_diseases_biospecimen_types as $key => $diseases_biospecimen_type) {
            $biospecimen_type = $biospecimenTypesById->{$diseases_biospecimen_type['sample_id']};
            $po_diseases_biospecimen_types[$key]['biospecimen_type'] = $biospecimen_type['biospecimen_type'];
            $modification = $sampleModsById->{$diseases_biospecimen_type['mod_id']};
            $po_diseases_biospecimen_types[$key]['modification'] = $modification['modification'];
        }

        $diseases_biospecimen_types_rus = App::call()->diseasesBiospecimenTypesRepository->getWhere(['order_id' => $order[0]['proj_id']]);
        foreach ($diseases_biospecimen_types as $key => $diseases_biospecimen_type) {
            $biospecimen_type = $biospecimenTypesById->{$diseases_biospecimen_type['biospecimen_type_id']};
            $diseases_biospecimen_types[$key]['biospecimen_type'] = $biospecimen_type['biospecimen_type'];
            $diseases_biospecimen_types_rus[$key]['biospecimen_type'] = $biospecimen_type['biospecimen_type_russian'];
            $modification = $sampleModsById->{$diseases_biospecimen_type['mod_id']};
            $diseases_biospecimen_types[$key]['modification'] = $modification['modification'];
            $diseases_biospecimen_types_rus[$key]['modification'] = $modification['modification'];
        }
        $user_id = App::call()->session->getSession('user_id');
        $history_quotes = [];
        $historyQuoteIds = [];
        foreach ($order_diseases as $order_disease) {
            $disease_biospecimen_types = App::call()->diseasesBiospecimenTypesRepository->getWhere(
                [
                    'order_id' => $order[0]['proj_id'],
                    'disease_id' => $order_disease['disease_id']
                ]);
            foreach ($disease_biospecimen_types as $disease_biospecimen_type) {
                $quotesByDiseaseAndSamples = App::call()->quoteRepository->getHistoryQuotesByDiseaseAndSampleIdsAndUser($order_disease['disease_id'], $disease_biospecimen_type['biospecimen_type_id'], $user_id);
                foreach ($quotesByDiseaseAndSamples as $quote) {
                    if (!array_search($quote['id'], $historyQuoteIds)) {
                        $historyQuoteIds[] = $quote['id'];
                        $history_quotes[] = $quote;
                    }
                }
            }
        }


        foreach ($history_quotes as $key => $quote) {
//            $disease = $orderDiseasesById->{$quote['disease_id']};
            $disease_name = $diseasesById->{$quote['disease_id']};
            $history_quotes[$key]['disease'] = "{$disease_name['disease_name']} ({$disease_name['disease_name_russian_old']})";
            $history_quotes[$key]['disease_id_db'] = $disease['disease_id'];
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


        echo $this->render($this->folder . 'Quote', [
            'id' => $id,
            'order' => $order,
            'order_diseases' => json_encode($order_diseases, JSON_UNESCAPED_UNICODE),
            'diseases_biospecimen_types' => json_encode($diseases_biospecimen_types, JSON_UNESCAPED_UNICODE),
            'po_diseases_biospecimen_types' => json_encode($po_diseases_biospecimen_types, JSON_UNESCAPED_UNICODE),
            'order_diseases_arr' => $order_diseases,
            'diseases_biospecimen_types_arr' => $diseases_biospecimen_types,
            'biospecimen_types' => json_encode($biospecimen_types, JSON_UNESCAPED_UNICODE),
            'files' => $files,
            'role' => $role,
            'history_quotes' => $history_quotes,
            'po_diseases' => json_encode($po_diseasesArray, JSON_UNESCAPED_UNICODE),
        ]);

    }


    public function actionChangeStatus()
    {

        if (isset(App::call()->request->getParams()['user_id'])) {
            $user_id = App::call()->request->getParams()['user_id'];
        } else {
            $user_id = App::call()->session->getSession('user_id');
        }

        $proj_id = App::call()->request->getParams()['proj_id'];
        $status_id = App::call()->request->getParams()['status_id'];

        $worksheets = App::call()->poworksheetsRepository->getWhere([
            'proj_id' => $proj_id,
            'user_id' => $user_id,
        ]);

        $comments = App::call()->request->getParams()['comments'];

        $worksheets_id = $worksheets[0]['id'];
        $worksheets = App::call()->poworksheetsRepository->getObject($worksheets_id);
        $worksheets->status_id = $status_id;
        $worksheets->comments = $comments;
        App::call()->poworksheetsRepository->save($worksheets);
        echo json_encode(['result' => true]);
    }


    public function actionClose()
    {


        $user_id = App::call()->session->getSession('user_id');
        $proj_id = App::call()->request->getParams()['proj_id'];

        $worksheets = App::call()->poworksheetsRepository->getWhere([
            'proj_id' => $proj_id,
            'user_id' => $user_id
        ]);
        $worksheets_id = $worksheets[0]['id'];
        $worksheets = App::call()->poworksheetsRepository->getObject($worksheets_id);
        $worksheets->status_id = 3; // статус отказ от квоты
        $worksheets->comments = 'Выполнение не возможно';
        App::call()->poworksheetsRepository->save($worksheets);

    }


}


