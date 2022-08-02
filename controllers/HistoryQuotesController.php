<?php

namespace app\controllers;

use app\engine\App;


class HistoryQuotesController extends Controller
{
    protected $layout = 'admin';
    protected $defaultAction = 'HistoryQuotes';
    protected $render = 'HistoryQuotes/HistoryQuotes';

    public function actionHistoryQuotes()
    {
        $script = [];
        $fr_scripts = App::call()->companyRepository->getAllSort();
        for ($i = 0; $i < count($fr_scripts); $i++) {
            $script[] = $fr_scripts[$i];
        }
        $diseases = App::call()->diseaseRepository->getAll();
        $biospecimen_types = App::call()->biospecimenTypeRepository->getAll();

        echo $this->render($this->render, [
            'script' => $script,
            'diseases' => $diseases,
            'biospecimen_types' => $biospecimen_types
        ]);
    }

    public function actionOldHistoryQuotes()
    {
        $script = [];
        $fr_scripts = App::call()->companyRepository->getAllSort();
        for ($i = 0; $i < count($fr_scripts); $i++) {
            $script[] = $fr_scripts[$i];
        }
        $diseases = App::call()->diseaseRepository->getAll();
        $biospecimen_types = App::call()->biospecimenTypeRepository->getAll();

        echo $this->render('HistoryQuotes/oldHistoryQuotes', [
            'script' => $script,
            'diseases' => $diseases,
            'biospecimen_types' => $biospecimen_types
        ]);
    }

    public function actionGetAll()
    {
        $array = App::call()->ordersRepository->getForQuotesTable();
        $order_diseases = new \stdClass();
        $json['data'] = [];
        $j = 0;
        for ($i = 0; $i < count($array); $i++) {
            $row = $array[$i];
            if (!property_exists($order_diseases, $row['proj_id'])) {
                $order_diseases->{$row['proj_id']} = new \stdClass();
                foreach ($row as $key => $value) {
                    if (!in_array($key, ['disease_id', 'disease_name', 'disease_name_russian_old', 'biospecimen_type_id', 'biospecimen_type']))
                        $json['data'][$j][$key] = $value;
                }
                $j++;
            }
            if (!property_exists($order_diseases->{$row['proj_id']}, $row['disease_id'])) {
                $order_diseases->{$row['proj_id']}->{$row['disease_id']} = new \stdClass();
                $order_diseases->{$row['proj_id']}->{$row['disease_id']}->name = $row['disease_name'];
                $order_diseases->{$row['proj_id']}->{$row['disease_id']}->name_russian_old = $row['disease_name_russian_old'];
                $order_diseases->{$row['proj_id']}->{$row['disease_id']}->biospecimen_types = new \stdClass();
            }
            if ($row['biospecimen_type_id'] !== null)
                $order_diseases->{$row['proj_id']}->{$row['disease_id']}->biospecimen_types->{$row['biospecimen_type_id']} = $row['biospecimen_type'];
        }
        foreach ($json['data'] as $key => $order) {
            $json['data'][$key]['diseases'] = $order_diseases->{$order['proj_id']};
        }
        echo json_encode($json);
    }
    public function actionGetAllWithFiles()
    {
        $array = App::call()->ordersRepository->getForQuotesTable();
        $order_diseases = new \stdClass();
        $json['data'] = [];
        $j = 0;
        $files = App::call()->filesRepository->getAll();
        $files_by_proj_id = new \stdClass();
        foreach ($files as $file) {
            if (!property_exists($files_by_proj_id, $file['proj_id'])) {
                $files_by_proj_id->{$file['proj_id']} = '';
            }
            $files_by_proj_id->{$file['proj_id']} .= " " . $file['name'];
        }
        for ($i = 0; $i < count($array); $i++) {
            $row = $array[$i];
            if (!property_exists($order_diseases, $row['proj_id'])) {
                $order_diseases->{$row['proj_id']} = new \stdClass();
                foreach ($row as $key => $value) {
                    if (!in_array($key, ['disease_id', 'disease_name', 'disease_name_russian_old', 'biospecimen_type_id', 'biospecimen_type']))
                        $json['data'][$j][$key] = $value;
                }
                $j++;
            }
            if (!property_exists($order_diseases->{$row['proj_id']}, $row['disease_id'])) {
                $order_diseases->{$row['proj_id']}->{$row['disease_id']} = new \stdClass();
                $order_diseases->{$row['proj_id']}->{$row['disease_id']}->name = $row['disease_name'];
                $order_diseases->{$row['proj_id']}->{$row['disease_id']}->name_russian_old = $row['disease_name_russian_old'];
                $order_diseases->{$row['proj_id']}->{$row['disease_id']}->biospecimen_types = new \stdClass();
            }
            if ($row['biospecimen_type_id'] !== null)
                $order_diseases->{$row['proj_id']}->{$row['disease_id']}->biospecimen_types->{$row['biospecimen_type_id']} = $row['biospecimen_type'];
        }
        foreach ($json['data'] as $key => $order) {
            $json['data'][$key]['diseases'] = $order_diseases->{$order['proj_id']};
            $json['data'][$key]['files'] = $files_by_proj_id->{$order['proj_id']};
        }
        echo json_encode($json);
    }


    public function actiongetAlls()
    {
        $text = App::call()->request->getParams()['text'];
        $mkb = App::call()->mkbRepository->getLike($text);
        echo json_encode($mkb);
    }

}
