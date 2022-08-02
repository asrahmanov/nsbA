<?php
//
//require '../vendor/autoload.php';
//
//use PhpOffice\PhpSpreadsheet\Spreadsheet;
//use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
//use PhpOffice\PhpSpreadsheet\IOFactory;
//
//use app\engine\App;
//use app\models\entities\Sites;
//
//$reader = PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
//$reader->setReadDataOnly(TRUE);
//$spreadsheet = $reader->load("COMPANY_need.xlsx");
//
//$dataArray = $spreadsheet->getActiveSheet()
//    ->rangeToArray(
//        'B1:CG1054',     // The worksheet range that we want to retrieve
//        '',        // Value that should be returned for empty cells
//        false,        // Should formulas be calculated (the equivalent of getCalculatedValue() for each cell)
//        false,        // Should values be formatted (the equivalent of getFormattedValue() for each cell)
//        true         // Should the array be indexed by cell row and cell column
//    );
//$numrows = count($dataArray);
//
//$addrs = [];
//
//for ($i = 0; $i < $numrows; $i++) {
//    $row = $dataArray[$i];
//    if (is_numeric($row['AQ'])) {
//        $site_id = $row['AQ'];
//        $addrs[$site_id] = $row['J'];
//        for ($j = 1; $dataArray[$i + $j]['AQ'] === '' && $dataArray[$i + $j]['J'] !== ''; $j++) {
//            $nextRow = $dataArray[$i + $j];
//            $addrs[$site_id] .= ' ' . $nextRow['J'];
//        }
//        echo $site_id . '<br>';
//        $site = App::call()->sitesRepository->getObject($site_id);
//        var_dump($site);
//        $site->addr = $addrs[$site_id];
//        App::call()->sitesRepository->save($site);
//    }
//}
//
//
//
//echo '<pre>';
////var_dump($dataArray);
////var_dump($addrs);
//echo '</pre>';
