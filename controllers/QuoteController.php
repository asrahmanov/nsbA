<?php

namespace app\controllers;

use app\engine\App;
use app\models\entities\Quote;


class QuoteController extends Controller
{

    protected $layout = 'vue';
    protected $folder = 'quote/';
    protected $defaultAction = 'Quote';

    public function actionQuote() {

        echo 'Упс';

    }

    public function actionGetbyFr() {
        $proj_id = App::call()->request->getParams()['proj_id'];
        if(isset(App::call()->request->getParams()['user_id'])){
            $user_id = App::call()->request->getParams()['user_id'];
        } else {
            $user_id = App::call()->session->getSession('user_id');
        }
        $result = App::call()->quoteRepository->getUsersQuotebyProjId($user_id,$proj_id);

        foreach ($result as $key => $quote) {
            $disease = App::call()->orderDiseasesRepository->getOne($quote['disease_id']);
            $disease_name = App::call()->diseaseRepository->getOne($disease['disease_id']);
            $result[$key]['disease'] = "{$disease_name['disease_name']} ({$disease_name['disease_name_russian_old']})";
            $result[$key]['disease_id_db'] = $disease['disease_id'];
            $sample = App::call()->diseasesBiospecimenTypesRepository->getOne($quote['sample_id']);
            $sample_name = App::call()->biospecimenTypeRepository->getOne($sample['biospecimen_type_id']);
            $mod_name = App::call()->sampleModRepository->getOne($sample['mod_id']);
            $result[$key]['sample'] = "{$sample_name['biospecimen_type']}  {$sample_name['biospecimen_type_russian']} {$mod_name['modification']}";

            $doctor_payments = App::call()->quoteDoctorRepository->getWhere(['quote_id' => $quote['id']]);
            foreach ($doctor_payments as $index => $doctor_payment) {
                $doctor = App::call()->companiesContactsRepository->getOne($doctor_payment['doc_id']);
                $doctor_payments[$index]['doc_name'] = "{$doctor['lastname']} {$doctor['firstname']} {$doctor['patronymic']}";
            }
            $result[$key]['doctor_payments'] = $doctor_payments;

            $disease_biospecimen_types = App::call()->quoteSampleRepository->getWhere(['quote_id' => $quote['id']]);
            foreach ($disease_biospecimen_types as $index => $disease_biospecimen_type) {
                $biospecimen_type = App::call()->biospecimenTypeRepository->getOne($disease_biospecimen_type['biospecimen_type_id']);
                $disease_biospecimen_types[$index]['biospecimen_type'] = $biospecimen_type['biospecimen_type'];
                $disease_biospecimen_types[$index]['biospecimen_type_russian'] = $biospecimen_type['biospecimen_type_russian'];
                $mod = App::call()->sampleModRepository->getOne($disease_biospecimen_type['mod_id']);
                $disease_biospecimen_types[$index]['mod'] = $mod['modification'];
                $disease_biospecimen_types[$index]['enabled'] = true;
                $disease_biospecimen_types[$index]['db_id'] = $disease_biospecimen_type['id'];
            }
            $result[$key]['samples_table'] = $disease_biospecimen_types;
        }
        echo json_encode(['result' => $result]);
    }

    public function actionSamplesInfo() {
        $quote_id = App::call()->request->getParams()['id'];
        $quote = App::call()->quoteRepository->getOne($quote_id);
        $order_disease = App::call()->orderDiseasesRepository->getOne($quote['disease_id']);
        // Образцы, подставляемые по умолчанию из fr
        $diseases_biospecimen_types_init = App::call()->diseasesBiospecimenTypesRepository
            ->getWhere([
                'order_id' => $quote['proj_id'],
                'disease_id' => $order_disease['disease_id']
            ]);
        // Образцы в квоте, включая добавленные вручную
        $quote_samples = App::call()->quoteSampleRepository->getWhere(['quote_id' => $quote_id]);
        $sample_ids_present = [];
        foreach ($quote_samples as $quote_sample) {
            $sample_ids_present[] = $quote_sample['biospecimen_type_id'];
        }
        $quote_samples_with_statuses = [];
        $quote_samples_from_init = [];
        // Среди образцов квоты отсеиваем добавленные из fr при инициализации, добавляя удаленные
        foreach ($diseases_biospecimen_types_init as $diseases_biospecimen_type) {
            if (array_search($diseases_biospecimen_type['biospecimen_type_id'], $sample_ids_present) !== false) {
                $diseases_biospecimen_type['status'] = 'initial';
                $quote_samples_from_init[] = $diseases_biospecimen_type['biospecimen_type_id'];
            } else {
                $diseases_biospecimen_type['status'] = 'deleted';
            }
            $quote_samples_with_statuses[] = $diseases_biospecimen_type;
        }
        // Остаются добавленные вручную
        $manual_samples = array_diff($sample_ids_present, $quote_samples_from_init);
        foreach ($quote_samples as $quote_sample) {
            if (array_search($quote_sample['biospecimen_type_id'], $manual_samples)) {
                $quote_sample['status'] = 'manual';
                $quote_samples_with_statuses[] = $quote_sample;
            }
        }
        foreach ($quote_samples_with_statuses as $i => $qs) {
            $biospecimen_type = App::call()->biospecimenTypeRepository->getOne($qs['biospecimen_type_id']);
            $quote_samples_with_statuses[$i]['biospecimen_type'] = $biospecimen_type['biospecimen_type'];
            $quote_samples_with_statuses[$i]['biospecimen_type_russian'] = $biospecimen_type['biospecimen_type_russian'];
            $mod = App::call()->sampleModRepository->getOne($qs['mod_id']);
            $quote_samples_with_statuses[$i]['mod'] = $mod['modification'];
        }
        echo json_encode(['result' => $quote_samples_with_statuses]);
    }

    public function actionSave()
    {
        if (isset(App::call()->request->getParams()['id'])) {
            $id = App::call()->request->getParams()['id'];
            $quote = App::call()->quoteRepository->getObject($id);
        } else {
            $quote = New Quote();
        }

        if(isset(App::call()->request->getParams()['user_id'])){
            $user_id = App::call()->request->getParams()['user_id'];
        } else {
            $user_id = App::call()->session->getSession('user_id');
        }
        $quote->user_id = $user_id;

        if (isset(App::call()->request->getParams()['proj_id'])) {
            $proj_id = App::call()->request->getParams()['proj_id'];
            $quote->proj_id = $proj_id;
        }
        if (isset(App::call()->request->getParams()['site_id'])) {
            $site_id = App::call()->request->getParams()['site_id'];
            $quote->site_id = $site_id;
        }
        if (isset(App::call()->request->getParams()['value_mount'])) {
            $value_mount = App::call()->request->getParams()['value_mount'];
            $quote->value_mount = $value_mount;
        }
        if (isset(App::call()->request->getParams()['days'])) {
            $days = App::call()->request->getParams()['days'];
            $quote->days = $days;
        }
        if (isset(App::call()->request->getParams()['price'])) {
            $price = App::call()->request->getParams()['price'];
            $quote->price = $price;
        }
        if (isset(App::call()->request->getParams()['price_doc'])) {
            $price_doc = App::call()->request->getParams()['price_doc'];
            $quote->price_doc = $price_doc;
        }
        if (isset(App::call()->request->getParams()['doc_name'])) {
            $doc_name = App::call()->request->getParams()['doc_name'];
            $quote->doc_name = $doc_name;
        }
        if (isset(App::call()->request->getParams()['doc_id'])) {
            $doc_id = App::call()->request->getParams()['doc_id'];
            $quote->doc_id = $doc_id;
        }
        if (isset(App::call()->request->getParams()['comment'])) {
            $comment = App::call()->request->getParams()['comment'];
            $quote->comment = $comment;
        }
        if (isset(App::call()->request->getParams()['disease_id'])) {
            $disease_id = App::call()->request->getParams()['disease_id'];
            $quote->disease_id = $disease_id;
        }
        if (isset(App::call()->request->getParams()['sample_id'])) {
            $sample_id = App::call()->request->getParams()['sample_id'];
            $quote->sample_id = $sample_id;
        }
//        if (isset(App::call()->request->getParams()['created_at'])) {
        $created_at = date('Y-m-d H:i:s');
        $quote->created_at = $created_at;
//        }
        $result = App::call()->quoteRepository->save($quote);
        if($result > 0){
            // При добавление новый квоты статус меняется на отработан
            $worksheets = App::call()->worksheetsRepository->getWhere([
                'proj_id' => $proj_id,
                'user_id' => $user_id
                ]);
            $worksheets_id = $worksheets[0]['id'];
            $worksheets = App::call()->worksheetsRepository->getObject($worksheets_id);
            $worksheets->status_id = 2;
            App::call()->worksheetsRepository->save($worksheets);
        }
        echo json_encode(['result' => $result ]);
    }

    public function actionDell()
    {
        $id = App::call()->request->getParams()['id'];
        $quote = App::call()->quoteRepository->getObject($id);
        $result = App::call()->quoteRepository->delete($quote);
        echo json_encode(['result' => $result ]);
    }

}


