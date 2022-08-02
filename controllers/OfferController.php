<?php

namespace app\controllers;

use app\engine\App;
use app\models\entities\Files;
use app\models\entities\Orders;
use app\models\entities\Priority;
use Dompdf\Dompdf;
use Mpdf\Mpdf;

class OfferController extends Controller
{
    protected $layout = 'admin';
    protected $defaultAction = 'offer';
    protected $render = 'Commercial/offer';


    public function actionOffer()
    {
        echo $this->render($this->render, [
            'pach' => $_SERVER["DOCUMENT_ROOT"]
        ]);
    }

    public function actionCreate()
    {
        $proj_id = App::call()->request->getParams()['proj_id'];
        $fr = App::call()->ordersRepository->GetOrdersOne($proj_id);

        $script_id = $fr['fr_script_id'];
        $company = App::call()->companyRepository->getOne($script_id);
        $currency = $company['currency'];

        if ($currency == '') {
            $currency = 'USD';
        } else {
            $currency = App::call()->currencyRepository->getOne($currency);
        }
        $currency = $currency['currency'];

        echo $this->render($this->render, [
            'fr' => $fr,
            'script_id' => $script_id,
            'currency' => $currency

        ]);
    }

    public function actionGeneratePdf()
    {
        $server = $_SERVER['DOCUMENT_ROOT'];


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
                                    page-break-inside: avoid
                                    } 
                                    .text {
                                    font-family: Helvetica Neue,Helvetica,Arial,sans-serif; 
                                    font-size: 10px !important;
                                    }
                                    
                                    .xl90 {
                                        background: #44546A;
                                        color: white;
                                        font-weight: 700;
                                       font-family: Helvetica Neue,Helvetica,Arial,sans-serif; 
                                        padding: 5px;
                                        text-align: center;
                                        font-size: 10px !important;
                                        vertical-align: center;
                                    }

                                    .xl91 {
                                        background: #DDEBF7;
                                        color: black;
                                       font-family: Helvetica Neue,Helvetica,Arial,sans-serif; 
                                        padding: 5px;
                                        text-align: center;
                                        font-size: 10px !important;
                                        vertical-align: center;
                                    }

                                    table {
                                        width: 100%;
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
                                        font-family: Calibri, sans-serif;
                                        border: 0.5pt solid #000000;
                                        border-spacing: 0;
                                        mso-pattern: black none;
                                        white-space: normal;
                                        text-align: center;
                                        vertical-align: center;
                                    }

                                </style>";


        $header = "<table width=\"100%\" border='0'>
                                    <tbody>
                                    <tr>
                                        <td><img src=\"{$server}/app-assets/img/offer/offer_logo2.png\" width=\"150px\" alt=\"\" style='border: none'></td>
                                        <td><img src=\"{$server}/app-assets/img/offer/offer_quotation.png\" width=\"150px\"  alt=\"\" style='border: none'></td>
                                        <td><img src=\"{$server}/app-assets/img/offer/offer_fon.png\" height='100px' alt=\"\" style='border: none'></td>
                                    </tr>
                                    </tbody>
                                </table><br>";

        $date = App::call()->request->getParams()['date_offer'];
        $document = App::call()->request->getParams()['document'];
        $distribution = App::call()->request->getParams()['distribution'];

        $scripts_staff_id = App::call()->request->getParams()['scripts_staff_id'];
        $companyUser = App::call()->companyStaffRepository->getOne($scripts_staff_id);
        if ($companyUser) {
            $name = $companyUser['name'];
            $position = $companyUser['position'];
            $attention = "$name $position";
        } else {
            $attention = '';
        }

        $client_id = App::call()->request->getParams()['client_id'];
        $valid_for = App::call()->request->getParams()['date_valid'];


        $user_id = App::call()->request->getParams()['user_id'];
        $userInfo = App::call()->usersRepository->getOne($user_id);
        if ($userInfo) {
            $user = "{$userInfo['en_name']}, {$userInfo['en_position']}";
        } else {
            $user = '';
        }

        if (isset(App::call()->request->getParams()['table_body'])) {
            $table_body = App::call()->request->getParams()['table_body'];
        } else {
            $table_body = '';
        }


        // Центаральная часть таблицы
        $table_one = "  <table width=\"100%\" style='font-family: Calibri,Candara,Segoe,Segoe UI,Optima,Arial,sans-serif;'>
                                    <thead>
                                    <tr>
                                        <td width=\"12%\"><b>Date</b></td>
                                        <td style=\"text-align: left;\">{$date}</td>
                                    </tr>
                                    <tr>
                                        <td width=\"12%\"><b>Document #</b></td>
                                        <td style=\"text-align: left;\">{$document}</td>
                                    </tr>
                                    <tr>
                                        <td width=\"12%\"><b>Distribution</b></td>
                                        <td style=\"text-align: left; font-family: Calibri,Candara,Segoe,Segoe UI,Optima,Arial,sans-serif; \">{$distribution}</td>
                                    </tr>
                                    <tr>
                                        <td width=\"12%\"><b>Attention to</b></td>
                                        <td style=\"text-align: left;\">{$attention}</td>
                                    </tr>
                                    <tr>
                                        <td width=\"12%\"><b>Client ID</b></td>
                                        <td style=\"text-align: left;\">{$client_id}</td>
                                    </tr>

                                    <tr>
                                        <td width=\"12%\"><b>Valid for</b></td>
                                        <td style=\"text-align: left;\">{$valid_for}</td>
                                    </tr>
                                    <tr>
                                        <td width=\"12%\"><b>Issued by</b></td>
                                        <td style=\"text-align: left;\">{$user}</td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>";


        $conditions = App::call()->request->getParams()['storage'];
        $turnaround = App::call()->request->getParams()['turnaround'];
        $currency = App::call()->request->getParams()['currency'];
        $totalPrice = App::call()->request->getParams()['totalPrice'];
        if ($turnaround == '') {
            $turnaround_title = '';
        } else {
            $turnaround_title = '<b>Turnaround time</b>';
        }
        $table_two = "<table width=\"100%\" >
                                    <tbody>
                                    <tr>
                                        <td width=\"12%\">$turnaround_title</td>
                                        <td style=\"text-align: right;\">$turnaround</td>
                                        <td style=\"text-align: right; padding-right: 10px\"><b>TOTAL per biospecimens
                                                procurement service ($currency) </b></td>
                                        <td class=\"xl90\" style=\"text-align: right; padding-right: 10px; \"> {$totalPrice}</td>
                                    </tr>
                                    <tr>
                                        <td width=\"12%\"><b>Storage conditions</b></td>
                                        <td style=\"text-align: right;\">{$conditions}</td>
                                        <td style=\"text-align: right; padding-right: 10px\"></td>
                                        <td></td>
                                    </tr>
                                    </tbody>
                                </table>";


        $incoterms = App::call()->request->getParams()['incoterms'];
        $shipping = App::call()->request->getParams()['shipping'];
        $courier_id = App::call()->request->getParams()['courier_id'];

        $courier = App::call()->courierRepository->getOne($courier_id);
        $courier = $courier['courier_name'];
        $estimated = App::call()->request->getParams()['estimated'];

        $newlines_1_text = "";
        $newlines_1 = App::call()->request->getParams()['newlines_1'];
        for ($i = 0; $i < $newlines_1; $i++) {
            $newlines_1_text .= "<br>";
        }
        $table3 = "$newlines_1_text<table width=\"70%\" cellpadding=\"0\" cellspacing=\"0\">
                                    <tbody>
                                    <tr class=\"xl90\">
                                        <td colspan=\"2\"><b>Shipping & Handling requirements</b></td>
                                    </tr>
                                    
                                    <tr class=\"xl91\">
                                        <td style=\"text-align: left; padding-left: 5px\"><b>Incoterms</b></td>
                                        <td style=\"text-align: left; padding-left: 5px\">{$incoterms}</td>
                                    </tr>
                                    <tr class=\"xl91\">
                                        <td style=\"text-align: left; padding-left: 5px\"><b>Storage conditions</b></td>
                                        <td style=\"text-align: left; padding-left: 5px\">{$shipping}</td>
                                    </tr>
                                    <tr class=\"xl91\">
                                        <td style=\"text-align: left; padding-left: 5px\"><b>Courier</b></td>
                                        <td style=\"text-align: left; padding-left: 5px\">{$courier}</td>
                                    </tr>
                                    <tr class=\"xl91\">
                                        <td style=\"text-align: left; padding-left: 5px\"><b>Estimated number of
                                                shipments</b></td>
                                        <td style=\"text-align: left; padding-left: 5px\">{$estimated}</td>
                                    </tr>
                                    </tbody>
                                </table><br>";

        $text_offer = App::call()->request->getParams()['text_offer'];
        $footer = "

<table width=\"100%\">
                                    <tbody>
                                    <tr>
                                        <td width=\"100%\" style='text-align: center;' colspan='2'><h3>Annex 1 to the Quotation<br><br>
                                        “On generation of bottom-line shipping cost per PO”</h3></td>
                                    </tr>
                                    <tr>
                                        <td width=\"100%\" colspan='2'>
                                            <p>Shipping costs mainly depend on (1) <i>the recipient country</i>, (2)
                                            <i>actual and bulk weight</i> (3) <i>number of shipments per PO</i>.</p>
                                            <p><b>The total shipment fee</b> will be billed based on actual costs incurred
                                             under the mutual agreement of both Client and Supplier (NBS).</p>
                                             <p><b>The cost of export permit</b> may be doubled in case there are two types of
                                             biospecimens to be shipped. In accordance with the requirements of the EEU customs
                                             legislation a separate permit is required for each of 2 classified types of samples:
                                             the first one is blood and its derivatives, the second one is tissues, including other biofluids and cells.</p>
                                             <p>In case of <b>DHL delivery</b>, the following but not the only additional special charges may occur:</p>
                                             <ul>
                                                 <li>The cost of CCD (cargo customs declaration) execution may be doubled if biospecimens
                                                 are to be delivered at different temperature conditions (dry ice and ambient),
                                                 which requires separate declaring of goods according to the courier company’s policy.</li>
                                                <li>There may be extra costs for dry ice processing at the airport: plus 85USD for each additional container.</li> 
                                                <li>USB-interface for each thermologger will cost additional 90USD, in case thermologger reading is required.</li>
                                            </ul>
                                            <p>In case of <b>WC delivery</b>, the following but not the only additional special charges may occur:</p>
                                            <ul>
                                                <li>In accordance with the courier company’s policy changing the shipping address affects the shipping cost.</li>
                                            </ul>
                                            <p><b>Please note, that NBS offers the option for consolidated shipping costs by combining
                                            <u>several Purchase Orders upon request and consent of the Client.</u></b></p>                                            
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width=\"30%\"><b>On behalf of I-BIOS LLC.:</b></td>
                                        <td rowspan=\"3\"  style=\"text-align: left\">
                                            <img src=\"{$server}/app-assets/img/offer/offer_ signature2.png\" width=\"168\" height=\"98\"  alt=\"\" style='border: none'>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width=\"12%\"><b>Dr. Rafayel Karapetyan</b> </td>
                                    </tr>
                                    <tr>
                                        <td width=\"12%\"><b>Director of Business Development </b></td>
                                    </tr>
                                    </tbody>
                                </table>";

        $shipping_id = App::call()->request->getParams()['shipping_id'];
        $shippingInfo = App::call()->shippingRepository->getOne($shipping_id);
        $courier_shipping_fee = $shippingInfo['shipping_name'];

        $count_shipping = App::call()->request->getParams()['count_shipping'];
        $shipping_fee_price = App::call()->request->getParams()['shipping_fee_price'];
        $shipping_fee_price_number = number_format($shipping_fee_price, 2);
        $shipping_fee_price_total = number_format($shipping_fee_price * $count_shipping, 2);


        $export_permit = App::call()->request->getParams()['export_permit'];
        $count_export_permit = App::call()->request->getParams()['count_export_permit'];
        $export_permit_price = App::call()->request->getParams()['export_permit_price'];
        $export_permit_price_number = number_format($export_permit_price, 2);
        $export_permit_price_total = number_format($export_permit_price * $count_export_permit, 2);

        $customs_clearance = App::call()->request->getParams()['customs_clearance'];
        $count_customs_clearance = App::call()->request->getParams()['count_customs_clearance'];
        $customs_clearance_price = App::call()->request->getParams()['customs_clearance_price'];
        $customs_clearance_price_number = number_format($customs_clearance_price, 2);
        $customs_clearance_price_total = number_format($customs_clearance_price * $count_customs_clearance, 2);


        $thermologger = App::call()->request->getParams()['thermologger'];
        $count_thermologger = App::call()->request->getParams()['count_thermologger'];
        $thermologger_price = App::call()->request->getParams()['thermologger_price'];
        $thermologger_price_number = number_format($thermologger_price, 2);
        $thermologger_price_total = number_format($thermologger_price * $count_thermologger, 2);


        $packaging = App::call()->request->getParams()['packaging'];
        $count_packaging = App::call()->request->getParams()['count_packaging'];
        $packaging_price = App::call()->request->getParams()['packaging_price'];
        $packaging_price_number = number_format($packaging_price, 2);
        $packaging_price_total = number_format($packaging_price * $count_packaging, 2);

        $totalShipping = App::call()->request->getParams()['total_shipping'];
        $payment_terms = App::call()->request->getParams()['payment_terms'];
        //$totalShipping = number_format($totalShipping, 2);

        $newlines_2 = App::call()->request->getParams()['newlines_2'];
        $newlines_2_text = '';
        for ($i = 0; $i < $newlines_2; $i++) {
            $newlines_2_text .= '<br>';
        }

        $block = "             <table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
                                    <tbody>
                                    <tr class=\"xl90\">
                                        <td><b>Shipping & Handling expense items</b></td>
                                        <td></td>
                                        <td>Quantity</td>
                                        <td>Price ({$currency})</td>
                                        <td>Total price ({$currency})</td>
                                    </tr>
                                    <tr class=\"xl91\">
                                        <td style=\"text-align: left; padding-left: 5px\"><b>Courier shipping fee </b>
                                        </td>
                                        <td nowrap style=\"text-align: left; padding-left: 5px\">$courier_shipping_fee </td>
                                        <td style=\"text-align: center; padding-left: 5px\">{$count_shipping}</td>
                                        <td style=\"text-align: right; padding-left: 5px\">{$shipping_fee_price_number}</td>
                                        <td style=\"text-align: right; padding-left: 5px\">{$shipping_fee_price_total}</td>
                                    </tr>
                                    <tr class=\"xl91\">
                                        <td style=\"text-align: left; padding-left: 5px\"><b>Export permit </b></td>
                                        <td style=\"text-align: left; padding-left: 5px\">{$export_permit}</td>
                                        <td style=\"text-align: center; padding-left: 5px\">{$count_export_permit}</td>
                                        <td style=\"text-align: right; padding-left: 5px\">{$export_permit_price_number}</td>
                                        <td style=\"text-align: right; padding-left: 5px\">{$export_permit_price_total}</td>
                                    </tr>
                                    <tr class=\"xl91\">
                                        <td style=\"text-align: left; padding-left: 5px\"><b>Customs clearance (broker's
                                                fee) </b></td>
                                        <td style=\"text-align: left; padding-left: 5px\">{$customs_clearance}</td>
                                        <td style=\"text-align: center; padding-left: 5px\">{$count_customs_clearance}</td>
                                        <td style=\"text-align: right; padding-left: 5px\">{$customs_clearance_price_number}</td>
                                        <td style=\"text-align: right; padding-left: 5px\">{$customs_clearance_price_total}</td>
                                    </tr>
                                    <tr class=\"xl91\">
                                        <td style=\"text-align: left; padding-left: 5px\"><b>Thermologger </b></td>
                                        <td style=\"text-align: left; padding-left: 5px\">{$thermologger}</td>
                                        <td style=\"text-align: center; padding-left: 5px\">{$count_thermologger}</td>
                                        <td style=\"text-align: right; padding-left: 5px\">{$thermologger_price_number}</td>
                                        <td style=\"text-align: right; padding-left: 5px\">{$thermologger_price_total}</td>
                                    </tr>
                                    <tr class=\"xl91\">
                                        <td style=\"text-align: left; padding-left: 5px\"><b>Packaging & handling </b>
                                        </td>
                                        <td style=\"text-align: left; padding-left: 5px\">{$packaging}</td>
                                        <td style=\"text-align: center; padding-left: 5px\">{$count_packaging}</td>
                                        <td style=\"text-align: right; padding-left: 5px\">{$packaging_price_number}</td>
                                        <td style=\"text-align: right; padding-left: 5px\">{$packaging_price_total}</td>
                                    </tr>
                                    </tbody>
                                </table>

                                <table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
                                    <tbody>
                                    <tr>
                                        <td><b>&nbsp;</b></td>
                                        <td style=\"text-align: right;\">&nbsp;</td>
                                        <td style=\"text-align: right; padding-right: 10px\"><b>ESTIMATE of total shipment
                                                costs ({$currency})</b></td>
                                        <td class=\"xl90\" style=\"text-align: right; padding-right: 10px; \"> {$totalShipping} </td>
                                    </tr>
                                    </tbody>
                                </table>
                                $newlines_2_text
                                <br>
                                <table width=\"100%\">
                                    <tbody>
                                    <tr class=\"xl90\">
                                        <td colspan=\"2\"><b>Payment policy </b></td>
                                    </tr>
                                    <tr class=\"xl91\">
                                        <td style=\"text-align: left; padding-left: 5px\"><b>Payment terms</b></td>
                                        <td style=\"text-align: left; padding-left: 5px\">{$payment_terms}</td>
                                    </tr>
                                    <tr class=\"xl91\">
                                        <td style=\"text-align: left; padding-left: 5px\"><b>Special terms</b></td>
                                        <td style=\"text-align: left; padding-left: 5px\">Actually incurred shipping cost
                                            will be invoiced and will depend on the weight and dimensions of shipping
                                            container(s), shipping destination and other conditions.
                                        </td>
                                    </tr>
                                    <tr class=\"xl91\">
                                        <td style=\"text-align: left; padding-left: 5px\"><b>&nbsp;</b></td>
                                        <td style=\"text-align: left; padding-left: 5px\">Estimated shipping costs are calculated on the assumption of one shipment per purchase order.</td>
                                    </tr>
                                    </tbody>
                                </table>
                                
                                <br>
                                         <table width=\"100%\">
                                    <tbody>
                                    <tr class=\"xl90\">
                                        <td colspan=\"2\"><b>Reservation status </b></td>
                                    </tr>
                                    <tr class=\"xl91\">
                                       
                                        <td colspan=\"2\" style=\"text-align: left; padding-left: 5px\">{$text_offer}</td>
                                    </tr>
                                    </tbody>
                                </table>
                                
                                
                                ";


        $table_body = "<table class=\"offer__table\" cellpadding=\"0\" cellspacing=\"0\" >{$table_body}</table>";
        echo $table_body;
        $html = "<html>" . $head . $style . $header . $table_one . $table_body . $table_two . $table3 . $block . $footer . "</html>";

        //$html = mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');

        $html = mb_convert_encoding($html, 'utf-8', mb_detect_encoding($html));
        //$html = mb_convert_encoding($html, "utf-8", "windows-1251");


        $dompdf = new Dompdf();
        $dompdf->loadHtml($html, 'UTF-8');
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $output = $dompdf->output();

//        $dompdf = new Mpdf();
//        $dompdf->WriteHTML('Жопа');
//        $output = $dompdf->Output();


        $pdfDate = DATE("d.m.y");
        $version = App::call()->request->getParams()['version'];

        $filename = "QUOTE I-BIOS {$document} {$version} {$pdfDate} .pdf";
        $proj_id = App::call()->request->getParams()['proj_id'];
        $dirname = 'upload/' . $proj_id . '/';

        if (file_exists($dirname)) {
            //echo "Директория существует";
        } else {
            mkdir($dirname);
        }

        $generateNamePdf = "upload/{$proj_id}/{$filename}";
        file_put_contents($generateNamePdf, $output);
        set_time_limit(0);
        $file = new Files();
        $file->name = $filename;
        $file->alias = $dirname;
        $file->proj_id = $proj_id;
        $file->info = 'quote';
        App::call()->filesRepository->save($file);
        return "Файл успешно загружен";


    }

    public function actionGenerateApePdf()
    {
        $server = $_SERVER['DOCUMENT_ROOT'];


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
                                        background: #44546A;
                                        color: white;
                                        font-weight: 700;
                                       font-family: Helvetica Neue,Helvetica,Arial,sans-serif; 
                                        padding: 5px;
                                        text-align: center;
                                        font-size: 10px !important;
                                        vertical-align: center;
                                    }

                                    .xl91 {
                                        background: #DDEBF7;
                                        color: black;
                                       font-family: Helvetica Neue,Helvetica,Arial,sans-serif; 
                                        padding: 5px;
                                        text-align: center;
                                        font-size: 10px !important;
                                        vertical-align: center;
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


        $header = "<table width=\"100%\" border='0'>
                                    <tbody>
                                    <tr>
                                        <td><img src=\"{$server}/app-assets/img/offer/offer_logo2.png\" width=\"150px\" alt=\"\" style='border: none'></td>
                                        <td><img src=\"{$server}/app-assets/img/offer/offer_quotation.png\" width=\"150px\"  alt=\"\" style='border: none'></td>
                                        <td><img src=\"{$server}/app-assets/img/offer/offer_fon.png\" height='100px' alt=\"\" style='border: none'></td>
                                    </tr>
                                    </tbody>
                                </table><br>";

        $date = App::call()->request->getParams()['date_offer'];
        $document = App::call()->request->getParams()['document'];
        $distribution = App::call()->request->getParams()['distribution'];

        $scripts_staff_id = App::call()->request->getParams()['scripts_staff_id'];
        $companyUser = App::call()->companyStaffRepository->getOne($scripts_staff_id);
        if ($companyUser) {
            $name = $companyUser['name'];
            $position = $companyUser['position'];
            $attention = "$name $position";
        } else {
            $attention = '';
        }

        $client_id = App::call()->request->getParams()['client_id'];
        $valid_for = App::call()->request->getParams()['date_valid'];


        $user_id = App::call()->request->getParams()['user_id'];
        $userInfo = App::call()->usersRepository->getOne($user_id);
        if ($userInfo) {
            $user = "{$userInfo['en_name']}, {$userInfo['en_position']}";
        } else {
            $user = '';
        }

        if (isset(App::call()->request->getParams()['table_body'])) {
            $table_body = App::call()->request->getParams()['table_body'];
        } else {
            $table_body = '';
        }


        // Центаральная часть таблицы
        $table_one = "  <table width=\"100%\" style='font-family: Calibri,Candara,Segoe,Segoe UI,Optima,Arial,sans-serif; '>
                                    <thead>
                                    <tr>
                                        <td width=\"12%\"><b>Date</b></td>
                                        <td style=\"text-align: left;\">{$date}</td>
                                    </tr>
                                    <tr>
                                        <td width=\"12%\"><b>Document #</b></td>
                                        <td style=\"text-align: left;\">{$document}</td>
                                    </tr>
                                    <tr>
                                        <td width=\"12%\"><b>Distribution</b></td>
                                        <td style=\"text-align: left; font-family: Calibri,Candara,Segoe,Segoe UI,Optima,Arial,sans-serif; \">{$distribution}</td>
                                    </tr>
                                    <tr>
                                        <td width=\"12%\"><b>Attention to</b></td>
                                        <td style=\"text-align: left;\">{$attention}</td>
                                    </tr>
                                    <tr>
                                        <td width=\"12%\"><b>Client ID</b></td>
                                        <td style=\"text-align: left;\">{$client_id}</td>
                                    </tr>

                                    <tr>
                                        <td width=\"12%\"><b>Valid for</b></td>
                                        <td style=\"text-align: left;\">{$valid_for}</td>
                                    </tr>
                                    <tr>
                                        <td width=\"12%\"><b>Issued by</b></td>
                                        <td style=\"text-align: left;\">{$user}</td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table><br>";


        $conditions = App::call()->request->getParams()['storage'];
        $turnaround = App::call()->request->getParams()['turnaround'];
        $currency = App::call()->request->getParams()['currency'];
        $totalPrice = App::call()->request->getParams()['totalPrice'];
        if ($turnaround == '') {
            $turnaround_title = '';
        } else {
            $turnaround_title = '<b>Turnaround time</b>';
        }

        $table_two = "<table width=\"100%\" >
                                    <tbody>
                                    <tr>
                                        <td width=\"12%\"><b>$turnaround_title</b></td>
                                        <td style=\"text-align: right;\">$turnaround</td>
                                        <td style=\"text-align: right; padding-right: 10px\"><b>TOTAL per biospecimens
                                                procurement service ($currency) </b></td>
                                        <td class=\"xl90\" style=\"text-align: right; padding-right: 10px; \"> $totalPrice</td>
                                    </tr>
                                    <tr>
                                        <td width=\"12%\"><b>Storage conditions</b></td>
                                        <td style=\"text-align: right;\">{$conditions}</td>
                                        <td style=\"text-align: right; padding-right: 10px\"></td>
                                        <td></td>
                                    </tr>
                                    </tbody>
                                </table>";


        $incoterms = App::call()->request->getParams()['incoterms'];
        $shipping = App::call()->request->getParams()['shipping'];
        $courier_id = App::call()->request->getParams()['courier_id'];

        $courier = App::call()->courierRepository->getOne($courier_id);
        $courier = $courier['courier_name'];
        $estimated = App::call()->request->getParams()['estimated'];

        $newlines_1_text = "";
        $newlines_1 = App::call()->request->getParams()['newlines_1'];
        for ($i = 0; $i < $newlines_1; $i++) {
            $newlines_1_text .= "<br>";
        }
        $table3 = "$newlines_1_text<table width=\"70%\" cellpadding=\"0\" cellspacing=\"0\">
                                    <tbody>
                                    <tr class=\"xl90\">
                                        <td colspan=\"2\"><b>Shipping & Handling requirements</b></td>
                                    </tr>
                                    
                                    <tr class=\"xl91\">
                                        <td style=\"text-align: left; padding-left: 5px\"><b>Incoterms</b></td>
                                        <td style=\"text-align: left; padding-left: 5px\">{$incoterms}</td>
                                    </tr>
                                    <tr class=\"xl91\">
                                        <td style=\"text-align: left; padding-left: 5px\"><b>Storage conditions</b></td>
                                        <td style=\"text-align: left; padding-left: 5px\">{$shipping}</td>
                                    </tr>
                                    <tr class=\"xl91\">
                                        <td style=\"text-align: left; padding-left: 5px\"><b>Courier</b></td>
                                        <td style=\"text-align: left; padding-left: 5px\">{$courier}</td>
                                    </tr>
                                    <tr class=\"xl91\">
                                        <td style=\"text-align: left; padding-left: 5px\"><b>Estimated number of
                                                shipments</b></td>
                                        <td style=\"text-align: left; padding-left: 5px\">{$estimated}</td>
                                    </tr>
                                    </tbody>
                                </table><br>";

        $footer = "<table width=\"100%\">
                                    <tbody>
                                    <tr>
                                        <td width=\"30%\"><b>On behalf of I-BIOS LLC.:</b></td>
                                        <td rowspan=\"3\"  style=\"text-align: left\">
                                            <img src=\"{$server}/app-assets/img/offer/offer_ signature2.png\" width=\"168\" height=\"98\"  alt=\"\" style='border: none'>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width=\"12%\"><b>Dr. Rafayel Karapetyan</b> </td>
                                    </tr>
                                    <tr>
                                        <td width=\"12%\"><b>Director of Business Development </b></td>
                                    </tr>
                                    </tbody>
                                </table>";

        $shipping_id = App::call()->request->getParams()['shipping_id'];
        $shippingInfo = App::call()->shippingRepository->getOne($shipping_id);
        $courier_shipping_fee = $shippingInfo['shipping_name'];

        $count_shipping = App::call()->request->getParams()['count_shipping'];
        $shipping_fee_price = App::call()->request->getParams()['shipping_fee_price'];
        $shipping_fee_price_number = number_format($shipping_fee_price, 2);
        $shipping_fee_price_total = number_format($shipping_fee_price * $count_shipping, 2);


        $export_permit = App::call()->request->getParams()['export_permit'];
        $count_export_permit = App::call()->request->getParams()['count_export_permit'];
        $export_permit_price = App::call()->request->getParams()['export_permit_price'];
        $export_permit_price_number = number_format($export_permit_price, 2);
        $export_permit_price_total = number_format($export_permit_price * $count_export_permit, 2);

        $customs_clearance = App::call()->request->getParams()['customs_clearance'];
        $count_customs_clearance = App::call()->request->getParams()['count_customs_clearance'];
        $customs_clearance_price = App::call()->request->getParams()['customs_clearance_price'];
        $customs_clearance_price_number = number_format($customs_clearance_price, 2);
        $customs_clearance_price_total = number_format($customs_clearance_price * $count_customs_clearance, 2);


        $thermologger = App::call()->request->getParams()['thermologger'];
        $count_thermologger = App::call()->request->getParams()['count_thermologger'];
        $thermologger_price = App::call()->request->getParams()['thermologger_price'];
        $thermologger_price_number = number_format($thermologger_price, 2);
        $thermologger_price_total = number_format($thermologger_price * $count_thermologger, 2);


        $packaging = App::call()->request->getParams()['packaging'];
        $count_packaging = App::call()->request->getParams()['count_packaging'];
        $packaging_price = App::call()->request->getParams()['packaging_price'];
        $packaging_price_number = number_format($packaging_price, 2);
        $packaging_price_total = number_format($packaging_price * $count_packaging, 2);

        $totalShipping = App::call()->request->getParams()['total_shipping'];
        $payment_terms = App::call()->request->getParams()['payment_terms'];
        //$totalShipping = number_format($totalShipping, 2);

        $newlines_2 = App::call()->request->getParams()['newlines_2'];
        $newlines_2_text = '';
        for ($i = 0; $i < $newlines_2; $i++) {
            $newlines_2_text .= '<br>';
        }

        $block = "             <table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
                                    <tbody>
                                    <tr class=\"xl90\">
                                        <td><b>Shipping & Handling expense items</b></td>
                                        <td></td>
                                        <td>Quantity</td>
                                        <td>Price ({$currency})</td>
                                        <td>Total price ({$currency})</td>
                                    </tr>
                                    <tr class=\"xl91\">
                                        <td style=\"text-align: left; padding-left: 5px\"><b>Courier shipping fee </b>
                                        </td>
                                        <td style=\"text-align: left; padding-left: 5px\">$courier_shipping_fee </td>
                                        <td style=\"text-align: center; padding-left: 5px\">{$count_export_permit}</td>
                                        <td style=\"text-align: right; padding-left: 5px\">{$shipping_fee_price_number}</td>
                                        <td style=\"text-align: right; padding-left: 5px\">{$shipping_fee_price_total}</td>
                                    </tr>
                                    <tr class=\"xl91\">
                                        <td style=\"text-align: left; padding-left: 5px\"><b>CITES Export permit </b></td>
                                        <td style=\"text-align: left; padding-left: 5px\">{$export_permit}</td>
                                        <td style=\"text-align: center; padding-left: 5px\">{$count_export_permit}</td>
                                        <td style=\"text-align: right; padding-left: 5px\">{$export_permit_price_number}</td>
                                        <td style=\"text-align: right; padding-left: 5px\">{$export_permit_price_total}</td>
                                    </tr>
                                    <tr class=\"xl91\">
                                        <td style=\"text-align: left; padding-left: 5px\"><b>Customs clearance (broker's
                                                fee) </b></td>
                                        <td style=\"text-align: left; padding-left: 5px\">{$customs_clearance}</td>
                                        <td style=\"text-align: center; padding-left: 5px\">{$count_customs_clearance}</td>
                                        <td style=\"text-align: right; padding-left: 5px\">{$customs_clearance_price_number}</td>
                                        <td style=\"text-align: right; padding-left: 5px\">{$customs_clearance_price_total}</td>
                                    </tr>
                                    <tr class=\"xl91\">
                                        <td style=\"text-align: left; padding-left: 5px\"><b>Thermologger </b></td>
                                        <td style=\"text-align: left; padding-left: 5px\">{$thermologger}</td>
                                        <td style=\"text-align: center; padding-left: 5px\">{$count_thermologger}</td>
                                        <td style=\"text-align: right; padding-left: 5px\">{$thermologger_price_number}</td>
                                        <td style=\"text-align: right; padding-left: 5px\">{$thermologger_price_total}</td>
                                    </tr>
                                    <tr class=\"xl91\">
                                        <td style=\"text-align: left; padding-left: 5px\"><b>Packaging & handling </b>
                                        </td>
                                        <td style=\"text-align: left; padding-left: 5px\">{$packaging}</td>
                                        <td style=\"text-align: center; padding-left: 5px\">{$count_packaging}</td>
                                        <td style=\"text-align: right; padding-left: 5px\">{$packaging_price_number}</td>
                                        <td style=\"text-align: right; padding-left: 5px\">{$packaging_price_total}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            

                                <table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
                                    <tbody>
                                    <tr>
                                        <td><b>&nbsp;</b></td>
                                        <td style=\"text-align: right;\">&nbsp;</td>
                                        <td style=\"text-align: right; padding-right: 10px\"><b>ESTIMATE of total shipment
                                                costs ({$currency})</b></td>
                                        <td class=\"xl90\" style=\"text-align: right; padding-right: 10px; \"> {$totalShipping} </td>
                                    </tr>
                                    </tbody>
                                </table>
                                $newlines_2_text
                                
                                <table width=\"100%\">
                                    <tbody>
                                    <tr class=\"xl90\">
                                        <td colspan=\"2\"><b>Payment policy </b></td>
                                    </tr>
                                    <tr class=\"xl91\">
                                        <td style=\"text-align: left; padding-left: 5px\"><b>Payment terms</b></td>
                                        <td style=\"text-align: left; padding-left: 5px\">{$payment_terms}</td>
                                    </tr>
                                    <tr class=\"xl91\">
                                        <td style=\"text-align: left; padding-left: 5px\"><b>Special terms</b></td>
                                        <td style=\"text-align: left; padding-left: 5px\">Actually incurred shipping cost
                                            will be invoiced and will depend on the weight and dimensions of shipping
                                            container(s), shipping destination and other conditions.
                                        </td>
                                    </tr>
                                    <tr class=\"xl91\">
                                        <td style=\"text-align: left; padding-left: 5px\"><b>&nbsp;</b></td>
                                        <td style=\"text-align: left; padding-left: 5px\">Estimated shipping costs are calculated on the assumption of one shipment per purchase order.</td>
                                    </tr>
                                    </tbody>
                                </table>
                                <br>
                                <br>";


        $table_body = "<table class=\"offer__table\" cellpadding=\"0\" cellspacing=\"0\" >{$table_body}</table>";

        $html = "<html>" . $head . $style . $header . $table_one . $table_body . $table_two . $table3 . $block . $footer . "</html>";

        $html = mb_convert_encoding($html, 'utf-8', mb_detect_encoding($html));

//        $dompdf = new \Mpdf\Mpdf();
//        $dompdf->WriteHTML('Жопа');
//        $output = $dompdf->Output();


        $dompdf = new Dompdf();
        $dompdf->loadHtml($html, 'UTF-8');

        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $output = $dompdf->output();



        $pdfDate = DATE("d.m.y");
        $version = App::call()->request->getParams()['version'];

        $filename = "QUOTE I-BIOS {$document} {$version} {$pdfDate} .pdf";
        $proj_id = App::call()->request->getParams()['proj_id'];
        $dirname = 'upload/' . $proj_id . '/';

        if (file_exists($dirname)) {
            //echo "Директория существует";
        } else {
            mkdir($dirname);
        }

        $generateNamePdf = "upload/{$proj_id}/{$filename}";
        file_put_contents($generateNamePdf, $output);
        set_time_limit(0);
        $file = new Files();
        $file->name = $filename;
        $file->alias = $dirname;
        $file->proj_id = $proj_id;
        App::call()->filesRepository->save($file);
        return "Файл успешно загружен";


    }

    public function actionPrintPdf()
    {
        $server = $_SERVER['DOCUMENT_ROOT'];


        $head = "
        <head>
         <meta http-equiv=Content-Type content=\"text/html; charset=utf-8\">
        </head>";

        $style = "   
  
   <style>  
                                       table {
                                       font-family: times
                                       }
                                    .xl90 {
                                        background: #44546A;
                                        color: white;
                                        font-weight: 700;
                                        font-family: Calibri, sans-serif;
                                        padding: 5px;
                                        text-align: center;
                                        font-size: 10px !important;
                                        vertical-align: center;
                                    }

                                    .xl91 {
                                        background: #DDEBF7;
                                        color: black;
                                        font-family: Calibri, sans-serif;
                                        padding: 5px;
                                        text-align: center;
                                        font-size: 10px !important;
                                        vertical-align: center;
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


        $header = "<table width=\"100%\" border='0'>
                                    <tbody>
                                    <tr>
                                        <td><img src=\"{$server}/app-assets/img/offer/offer_logo2.png\" width=\"150px\" alt=\"\" style='border: none'></td>
                                        <td><img src=\"{$server}/app-assets/img/offer/offer_quotation.png\" width=\"150px\"  alt=\"\" style='border: none'></td>
                                        <td><img src=\"{$server}/app-assets/img/offer/offer_fon.png\" height='100px' alt=\"\" style='border: none'></td>
                                    </tr>
                                    </tbody>
                                </table><br>";

        $date = App::call()->request->getParams()['date_offer'];
        $document = App::call()->request->getParams()['document'];
        $distribution = App::call()->request->getParams()['distribution'];

        $scripts_staff_id = App::call()->request->getParams()['scripts_staff_id'];
        $companyUser = App::call()->companyStaffRepository->getOne($scripts_staff_id);
        if ($companyUser) {
            $name = $companyUser['name'];
            $position = $companyUser['position'];
            $attention = "{$name} ${$position}";
        } else {
            $attention = '';
        }

        $client_id = App::call()->request->getParams()['client_id'];
        $valid_for = App::call()->request->getParams()['date_valid'];


        $user_id = App::call()->request->getParams()['user_id'];
        $userInfo = App::call()->usersRepository->getOne($user_id);
        if ($userInfo) {
            $user = "{$userInfo['en_name']}, {$userInfo['en_position']}";
        } else {
            $user = '';
        }

        if (isset(App::call()->request->getParams()['table_body'])) {
            $table_body = App::call()->request->getParams()['table_body'];
        } else {
            $table_body = '';
        }


        // Центаральная часть таблицы
        $table_one = "  <table width=\"100%\" style='font-family: Calibri,Candara,Segoe,Segoe UI,Optima,Arial,sans-serif; '>
                                    <thead>
                                    <tr>
                                        <td width=\"12%\"><b>Date</b></td>
                                        <td style=\"text-align: left;\">{$date}</td>
                                    </tr>
                                    <tr>
                                        <td width=\"12%\"><b>Document #</b></td>
                                        <td style=\"text-align: left;\">{$document}</td>
                                    </tr>
                                    <tr>
                                        <td width=\"12%\"><b>Distribution</b></td>
                                        <td style=\"text-align: left; font-family: Calibri,Candara,Segoe,Segoe UI,Optima,Arial,sans-serif; \">{$distribution}</td>
                                    </tr>
                                    <tr>
                                        <td width=\"12%\"><b>Attention to</b></td>
                                        <td style=\"text-align: left;\">{$attention}</td>
                                    </tr>
                                    <tr>
                                        <td width=\"12%\"><b>Client ID</b></td>
                                        <td style=\"text-align: left;\">{$client_id}</td>
                                    </tr>

                                    <tr>
                                        <td width=\"12%\"><b>Valid for</b></td>
                                        <td style=\"text-align: left;\">{$valid_for}</td>
                                    </tr>
                                    <tr>
                                        <td width=\"12%\"><b>Issued by</b></td>
                                        <td style=\"text-align: left;\">{$user}</td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table><br>";


        $conditions = App::call()->request->getParams()['storage'];
        $turnaround = App::call()->request->getParams()['turnaround'];
        $currency = App::call()->request->getParams()['currency'];
        $totalPrice = App::call()->request->getParams()['totalPrice'];


        $table_two = " <table width=\"100%\" >
                                    <tbody>
                                    <tr>
                                        <td width=\"12%\"><b>Turnaround time</b></td>
                                        <td style=\"text-align: right;\">{$turnaround}</td>
                                        <td style=\"text-align: right; padding-right: 10px\"><b>TOTAL per biospecimens
                                                procurement service ({$currency}) </b></td>
                                        <td class=\"xl90\" style=\"text-align: right; padding-right: 10px; \"> {$totalPrice}</td>
                                    </tr>
                                    <tr>
                                        <td width=\"12%\"><b>Storage conditions</b></td>
                                        <td style=\"text-align: right;\">{$conditions}</td>
                                        <td style=\"text-align: right; padding-right: 10px\"></td>
                                        <td></td>
                                    </tr>
                                    </tbody>
                                </table><br>";


        $incoterms = App::call()->request->getParams()['incoterms'];
        $shipping = App::call()->request->getParams()['shipping'];
        $courier_id = App::call()->request->getParams()['courier_id'];

        $courier = App::call()->courierRepository->getOne($courier_id);
        $courier = $courier['courier_name'];
        $estimated = App::call()->request->getParams()['estimated'];


        $table3 = " <table width=\"70%\" cellpadding=\"0\" cellspacing=\"0\">
                                    <tbody>
                                    <tr class=\"xl90\">
                                        <td colspan=\"2\"><b>Turnaround time</b></td>
                                    </tr>
                                    
                                    <tr class=\"xl91\">
                                        <td style=\"text-align: left; padding-left: 5px\"><b>Incoterms</b></td>
                                        <td style=\"text-align: left; padding-left: 5px\">{$incoterms}</td>
                                    </tr>
                                    <tr class=\"xl91\">
                                        <td style=\"text-align: left; padding-left: 5px\"><b>Storage conditions</b></td>
                                        <td style=\"text-align: left; padding-left: 5px\">{$shipping}</td>
                                    </tr>
                                    <tr class=\"xl91\">
                                        <td style=\"text-align: left; padding-left: 5px\"><b>Courier</b></td>
                                        <td style=\"text-align: left; padding-left: 5px\">{$courier}</td>
                                    </tr>
                                    <tr class=\"xl91\">
                                        <td style=\"text-align: left; padding-left: 5px\"><b>Estimated number of
                                                shipments</b></td>
                                        <td style=\"text-align: left; padding-left: 5px\">{$estimated}</td>
                                    </tr>
                                    </tbody>
                                </table>";


        $text_offer = App::call()->request->getParams()['text_offer'];
        $footer = "<table width=\"100%\">

                                    <tbody>
                                    <tr>
                                        <td width=\"100%\" style='text-align: center;'><h3>Annex 1 to the Quotation<br><br>
                                        “On generation of bottom-line shipping cost per PO”</h3></td>
                                    </tr>
                                    <tr>
                                        <td width=\"100%\">
                                            <p>Shipping costs mainly depend on (1) <i>the recipient country</i>, (2)
                                            <i>actual and bulk weight</i> (3) <i>number of shipments per PO</i>.</p>
                                            <p><b>The total shipment fee</b> will be billed based on actual costs incurred
                                             under the mutual agreement of both Client and Supplier (NBS).</p>
                                             <p><b>The cost of export permit</b> may be doubled in case there are two types of
                                             biospecimens to be shipped. In accordance with the requirements of the EEU customs
                                             legislation a separate permit is required for each of 2 classified types of samples:
                                             the first one is blood and its derivatives, the second one is tissues, including other biofluids and cells.</p>
                                             <p>In case of <b>DHL delivery</b>, the following but not the only additional special charges may occur:</p>
                                             <ul>
                                                 <li>The cost of CCD (cargo customs declaration) execution may be doubled if biospecimens
                                                 are to be delivered at different temperature conditions (dry ice and ambient),
                                                 which requires separate declaring of goods according to the courier company’s policy.</li>
                                                <li>There may be extra costs for dry ice processing at the airport: plus 85USD for each additional container.</li> 
                                                <li>USB-interface for each thermologger will cost additional 90USD, in case thermologger reading is required.</li>
                                            </ul>
                                            <p>In case of <b>WC delivery</b>, the following but not the only additional special charges may occur:</p>
                                            <ul>
                                                <li>In accordance with the courier company’s policy changing the shipping address affects the shipping cost.</li>
                                            </ul>
                                            <p><b>Please note, that NBS offers the option for consolidated shipping costs by combining
                                            <u>several Purchase Orders upon request and consent of the Client.</u></b></p>                                            
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width=\"30%\"><b>On behalf of I-BIOS LLC.:</b></td>
                                        <td rowspan=\"3\"  style=\"text-align: left\">
                                            <img src=\"{$server}/app-assets/img/offer/offer_ signature2.png\" width=\"168\" height=\"98\"  alt=\"\" style='border: none'>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width=\"12%\"><b>Dr. Rafayel Karapetyan</b> </td>
                                    </tr>
                                    <tr>
                                        <td width=\"12%\"><b>Director of Business Development </b></td>
                                    </tr>
                                    </tbody>
                                </table>";

        $shipping_id = App::call()->request->getParams()['shipping_id'];
        $shippingInfo = App::call()->shippingRepository->getOne($shipping_id);
        $courier_shipping_fee = $shippingInfo['shipping_name'];

        $count_shipping = App::call()->request->getParams()['count_shipping'];
        $shipping_fee_price = App::call()->request->getParams()['shipping_fee_price'];
        $shipping_fee_price_number = number_format($shipping_fee_price, 2);
        $shipping_fee_price_total = number_format($shipping_fee_price * $count_shipping, 2);


        $export_permit = App::call()->request->getParams()['export_permit'];
        $count_export_permit = App::call()->request->getParams()['count_export_permit'];
        $export_permit_price = App::call()->request->getParams()['export_permit_price'];
        $export_permit_price_number = number_format($export_permit_price, 2);
        $export_permit_price_total = number_format($export_permit_price * $count_export_permit, 2);

        $customs_clearance = App::call()->request->getParams()['customs_clearance'];
        $count_customs_clearance = App::call()->request->getParams()['count_customs_clearance'];
        $customs_clearance_price = App::call()->request->getParams()['customs_clearance_price'];
        $customs_clearance_price_number = number_format($customs_clearance_price, 2);
        $customs_clearance_price_total = number_format($customs_clearance_price * $count_customs_clearance, 2);


        $thermologger = App::call()->request->getParams()['thermologger'];
        $count_thermologger = App::call()->request->getParams()['count_thermologger'];
        $thermologger_price = App::call()->request->getParams()['thermologger_price'];
        $thermologger_price_number = number_format($thermologger_price, 2);
        $thermologger_price_total = number_format($thermologger_price * $count_thermologger, 2);


        $packaging = App::call()->request->getParams()['packaging'];
        $count_packaging = App::call()->request->getParams()['count_packaging'];
        $packaging_price = App::call()->request->getParams()['packaging_price'];
        $packaging_price_number = number_format($packaging_price, 2);
        $packaging_price_total = number_format($packaging_price * $count_packaging, 2);

        $totalShipping = App::call()->request->getParams()['total_shipping'];
        $payment_terms = App::call()->request->getParams()['payment_terms'];
        $totalShipping = number_format($totalShipping, 2);


        $block = "             <table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
                                    <tbody>
                                    <tr class=\"xl90\">
                                        <td><b>Shipping & Handling expense items</b></td>
                                        <td></td>
                                        <td>Quantity</td>
                                        <td>Price ({$currency})</td>
                                        <td>Total price ({$currency})</td>
                                    </tr>
                                    <tr class=\"xl91\">
                                        <td style=\"text-align: left; padding-left: 5px\"><b>Courier shipping fee </b>
                                        </td>
                                        <td nowrap style=\"text-align: left; padding-left: 5px\">$courier_shipping_fee </td>
                                        <td style=\"text-align: center; padding-left: 5px\">{$count_export_permit}</td>
                                        <td style=\"text-align: right; padding-left: 5px\">{$shipping_fee_price_number}</td>
                                        <td style=\"text-align: right; padding-left: 5px\">{$shipping_fee_price_total}</td>
                                    </tr>
                                    <tr class=\"xl91\">
                                        <td style=\"text-align: left; padding-left: 5px\"><b>Export permit </b></td>
                                        <td style=\"text-align: left; padding-left: 5px\">{$export_permit}</td>
                                        <td style=\"text-align: center; padding-left: 5px\">{$count_export_permit}</td>
                                        <td style=\"text-align: right; padding-left: 5px\">{$export_permit_price_number}</td>
                                        <td style=\"text-align: right; padding-left: 5px\">{$export_permit_price_total}</td>
                                    </tr>
                                    <tr class=\"xl91\">
                                        <td style=\"text-align: left; padding-left: 5px\"><b>Customs clearance (broker's
                                                fee) </b></td>
                                        <td style=\"text-align: left; padding-left: 5px\">{$customs_clearance}</td>
                                        <td style=\"text-align: center; padding-left: 5px\">{$count_customs_clearance}</td>
                                        <td style=\"text-align: right; padding-left: 5px\">{$customs_clearance_price_number}</td>
                                        <td style=\"text-align: right; padding-left: 5px\">{$customs_clearance_price_total}</td>
                                    </tr>
                                    <tr class=\"xl91\">
                                        <td style=\"text-align: left; padding-left: 5px\"><b>Thermologger </b></td>
                                        <td style=\"text-align: left; padding-left: 5px\">{$thermologger}</td>
                                        <td style=\"text-align: center; padding-left: 5px\">{$count_thermologger}</td>
                                        <td style=\"text-align: right; padding-left: 5px\">{$thermologger_price_number}</td>
                                        <td style=\"text-align: right; padding-left: 5px\">{$thermologger_price_total}</td>
                                    </tr>
                                    <tr class=\"xl91\">
                                        <td style=\"text-align: left; padding-left: 5px\"><b>Packaging & handling </b>
                                        </td>
                                        <td style=\"text-align: left; padding-left: 5px\">{$packaging}</td>
                                        <td style=\"text-align: center; padding-left: 5px\">{$count_packaging}</td>
                                        <td style=\"text-align: right; padding-left: 5px\">{$packaging_price_number}</td>
                                        <td style=\"text-align: right; padding-left: 5px\">{$packaging_price_total}</td>
                                    </tr>
                                    </tbody>
                                </table>
                                <br>

                                <table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
                                    <tbody>
                                    <tr>
                                        <td><b>&nbsp;</b></td>
                                        <td style=\"text-align: right;\">&nbsp;</td>
                                        <td style=\"text-align: right; padding-right: 10px\"><b>ESTIMATE of total shipment
                                                costs ($currency)</b></td>
                                        <td class=\"xl90\" style=\"text-align: right; padding-right: 10px; \"> {$totalShipping} </td>
                                    </tr>
                                    </tbody>
                                </table>

                                <table width=\"100%\">
                                    <tbody>
                                    <tr class=\"xl90\">
                                        <td colspan=\"2\"><b>Payment policy </b></td>
                                    </tr>
                                    <tr class=\"xl91\">
                                        <td style=\"text-align: left; padding-left: 5px\"><b>Payment terms</b></td>
                                        <td style=\"text-align: left; padding-left: 5px\">{$payment_terms}</td>
                                    </tr>
                                    <tr class=\"xl91\">
                                        <td style=\"text-align: left; padding-left: 5px\"><b>Special terms</b></td>
                                        <td style=\"text-align: left; padding-left: 5px\">Actually incurred shipping cost
                                            will be invoiced and will depend on the weight and dimensions of shipping
                                            container(s), shipping destination and other conditions.
                                        </td>
                                    </tr>
                                    <tr class=\"xl91\">
                                        <td style=\"text-align: left; padding-left: 5px\"><b>&nbsp;</b></td>
                                        <td style=\"text-align: left; padding-left: 5px\">Estimated shipping costs are calculated on the assumption of one shipment per purchase order.</td>
                                    </tr>
                                    </tbody>
                                </table>
                                <br>
                                    <table width=\"100%\">
                                    <tbody>
                                    <tr class=\"xl90\">
                                        <td colspan=\"2\"><b>Reservation status </b></td>
                                    </tr>
                                    <tr class=\"xl91\">
                                       
                                        <td colspan=\"2\" style=\"text-align: left; padding-left: 5px\">{$text_offer}</td>
                                    </tr>
                                    </tbody>
                                </table>
                                
                                
                                <br>
                                <br>";


        $table_body = "<table class=\"offer__table\" cellpadding=\"0\" cellspacing=\"0\" >{$table_body}</table>";

        $html = "<html>" . $head . $style . $header . $table_one . $table_body . $table_two . $table3 . $block . $footer . "</html>";

        //$html = mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');

        $html = mb_convert_encoding($html, 'utf-8', mb_detect_encoding($html));

        echo $this->render('orders/offerPrint', [
            'html' => $html
        ]);


    }

    public function actionGetAll()
    {
        $array = App::call()->offerRepository->getAll();
        echo json_encode(['result' => $array]);
    }

    public function actionGetAllTrueId()
    {
        $array = App::call()->offerRepository->getAllTrueId();
        echo json_encode(['result' => $array]);
    }

    public function actionGetbyId()
    {
        $proj_id = App::call()->request->getParams()['proj_id'];
        $array = App::call()->offerRepository->getWhere(['proj_id' => $proj_id]);
        echo json_encode(['result' => $array]);
    }

    public function actionSave()
    {
        if (isset(App::call()->request->getParams()['id'])) {

            $id = App::call()->request->getParams()['id'];
            $offer = App::call()->offerRepository->getObject($id);

            if (isset(App::call()->request->getParams()['date_offer'])) {
                $date_offer = App::call()->request->getParams()['date_offer'];
                $offer->date_offer = $date_offer;
            }


            if (isset(App::call()->request->getParams()['text_offer'])) {
                $text_offer = App::call()->request->getParams()['text_offer'];
                $offer->text_offer = $text_offer;
            }

            if (isset(App::call()->request->getParams()['scripts_staff_id'])) {
                $scripts_staff_id = App::call()->request->getParams()['scripts_staff_id'];
                $offer->scripts_staff_id = $scripts_staff_id;
            }

            if (isset(App::call()->request->getParams()['date_valid'])) {
                $date_valid = App::call()->request->getParams()['date_valid'];
                $offer->date_valid = $date_valid;
            }

            if (isset(App::call()->request->getParams()['user_id'])) {
                $user_id = App::call()->request->getParams()['user_id'];
                $offer->user_id = $user_id;
            }

            if (isset(App::call()->request->getParams()['turnaround'])) {
                $turnaround = App::call()->request->getParams()['turnaround'];
                $offer->turnaround = $turnaround;
            }

            if (isset(App::call()->request->getParams()['storage'])) {
                $storage = App::call()->request->getParams()['storage'];
                $offer->storage = $storage;
            }

            if (isset(App::call()->request->getParams()['incoterms'])) {
                $incoterms = App::call()->request->getParams()['incoterms'];
                $offer->incoterms = $incoterms;
            }

            if (isset(App::call()->request->getParams()['shipping'])) {
                $shipping = App::call()->request->getParams()['shipping'];
                $offer->shipping = $shipping;
            }

            if (isset(App::call()->request->getParams()['courier_id'])) {
                $courier_id = App::call()->request->getParams()['courier_id'];
                $offer->courier_id = $courier_id;
            }

            if (isset(App::call()->request->getParams()['estimated'])) {
                $estimated = App::call()->request->getParams()['estimated'];
                $offer->estimated = $estimated;
            }


            if (isset(App::call()->request->getParams()['shipping_id'])) {
                $shipping_id = App::call()->request->getParams()['shipping_id'];
                $offer->shipping_id = $shipping_id;
            }

            if (isset(App::call()->request->getParams()['count_shipping'])) {
                $count_shipping = App::call()->request->getParams()['count_shipping'];
                $offer->count_shipping = $count_shipping;
            }

            if (isset(App::call()->request->getParams()['export_permit'])) {
                $export_permit = App::call()->request->getParams()['export_permit'];
                $offer->export_permit = $export_permit;
            }

            if (isset(App::call()->request->getParams()['count_export_permit'])) {
                $count_export_permit = App::call()->request->getParams()['count_export_permit'];
                $offer->count_export_permit = $count_export_permit;
            }

            if (isset(App::call()->request->getParams()['customs_clearance'])) {
                $customs_clearance = App::call()->request->getParams()['customs_clearance'];
                $offer->customs_clearance = $customs_clearance;
            }

            if (isset(App::call()->request->getParams()['count_customs_clearance'])) {
                $count_customs_clearance = App::call()->request->getParams()['count_customs_clearance'];
                $offer->count_customs_clearance = $count_customs_clearance;
            }

            if (isset(App::call()->request->getParams()['thermologger'])) {
                $thermologger = App::call()->request->getParams()['thermologger'];
                $offer->thermologger = $thermologger;
            }

            if (isset(App::call()->request->getParams()['count_thermologger'])) {
                $count_thermologger = App::call()->request->getParams()['count_thermologger'];
                $offer->count_thermologger = $count_thermologger;
            }

            if (isset(App::call()->request->getParams()['packaging'])) {
                $packaging = App::call()->request->getParams()['packaging'];
                $offer->packaging = $packaging;
            }

            if (isset(App::call()->request->getParams()['count_packaging'])) {
                $count_packaging = App::call()->request->getParams()['count_packaging'];
                $offer->count_packaging = $count_packaging;
            }


            App::call()->offerRepository->save($offer);

        }


    }

}
