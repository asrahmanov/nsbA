<?php

namespace app\controllers;

use app\engine\App;
use app\models\entities\Files;

//use app\models\entities\Orders;
//use app\models\entities\Priority;
use Dompdf\Dompdf;

class OfferRuController extends Controller
{
//    protected $layout = 'admin';
//    protected $defaultAction = 'offer';
//    protected $render = 'Commercial/offer';

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
                                        <td> </td>
                                        <td><img src=\"{$server}/app-assets/img/offer/offer_fon.png\" height='100px' alt=\"\" style='border: none'></td>
                                    </tr>
                                    </tbody>
                                </table><br>";

        $date = App::call()->request->getParams()['date_offer'];
        $document = App::call()->request->getParams()['document'];
        $distribution = App::call()->request->getParams()['distribution'];


        $zabolevanie = App::call()->request->getParams()['zabolevanie'];
        $opis = App::call()->request->getParams()['opis'];
        $kol = App::call()->request->getParams()['kol'];
        $cenaEtapa = App::call()->request->getParams()['cenaEtapa'];
        $obstoimost = App::call()->request->getParams()['obstoimost'];



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
            $user = "{$userInfo['firstname']}  {$userInfo['lasttname']}";
        } else {
            $user = '';
        }

        if (isset(App::call()->request->getParams()['table_body'])) {
            $table_body = App::call()->request->getParams()['table_body'];
        } else {
            $table_body = '';
        }

        $date_n = explode("-", $date);
        $god = $date_n[0];
        $day = $date_n[2];
        $mes = $date_n[1];


        if($mes=="01"){$mes="Января"; $mes_dog = "01";};
        if($mes=="02"){$mes="Февраля";$mes_dog = "02";};
        if($mes=="03"){$mes="Марта";$mes_dog = "03";};
        if($mes=="04"){$mes="Апреля";$mes_dog = "04";};
        if($mes=="05"){$mes="Мая";$mes_dog = "05";};
        if($mes=="06"){$mes="Июня";$mes_dog = "06";};
        if($mes=="07"){$mes="Июля";$mes_dog = "07";};
        if($mes=="08"){$mes="Августа";$mes_dog = "08";};
        if($mes=="09"){$mes="Сентяря";$mes_dog = "09";};
        if($mes=="10"){$mes="Октября";$mes_dog = "10";};
        if($mes=="11"){$mes="Ноября";$mes_dog = "11";};
        if($mes=="12"){$mes="Декабря";$mes_dog = "12";};


        $date_valid = explode("-", $valid_for);
        $god_valid = $date_valid[0];
        $day_valid = $date_valid[2];
        $mes_valid = $date_valid[1];


        if($mes_valid=="01"){$mes_valid="Января";  };
        if($mes_valid=="02"){$mes_valid="Февраля"; };
        if($mes_valid=="03"){$mes_valid="Марта"; };
        if($mes_valid=="04"){$mes_valid="Апреля"; };
        if($mes_valid=="05"){$mes_valid="Мая";};
        if($mes_valid=="06"){$mes_valid="Июня";};
        if($mes_valid=="07"){$mes_valid="Июля";};
        if($mes_valid=="08"){$mes_valid="Августа";};
        if($mes_valid=="09"){$mes_valid="Сентяря";};
        if($mes_valid=="10"){$mes_valid="Октября";};
        if($mes_valid=="11"){$mes_valid="Ноября";};
        if($mes_valid=="12"){$mes_valid="Декабря";};




        // Центаральная часть таблицы
        $table_one = "  <table width=\"100%\" style='font-family: Calibri,Candara,Segoe,Segoe UI,Optima,Arial,sans-serif; '>
                                    <thead>
                                    <tr>
                                        <td width=\"33%\"><b>Дата</b></td>
                                        <td style=\"text-align: left;\">{$day} {$mes} {$god} г.</td>
                                    </tr>
                                    <tr>
                                        <td width=\"33%\"><b>№ запроса</b></td>
                                        <td style=\"text-align: left;\">{$document}</td>
                                    </tr>
                                    <tr>
                                        <td width=\"33%\"><b>Заказчик</b></td>
                                        <td style=\"text-align: left; font-family: Calibri,Candara,Segoe,Segoe UI,Optima,Arial,sans-serif; \">{$distribution}</td>
                                    </tr>
                                     <tr>
                                        <td width=\"33%\"><b>Представитель заказчика</b></td>
                                        <td style=\"text-align: left; font-family: Calibri,Candara,Segoe,Segoe UI,Optima,Arial,sans-serif; \">{$companyUser['position']}<br>
                                        {$companyUser['name']}
                                        </td>
                                    </tr>
                                    
                                    <tr>
                                        <td width=\"33%\"><b>Представитель исполнителя</b></td>
                                        <td style=\"text-align: left;\">{$userInfo['ru_position']} {$user}<br> {$userInfo['phone']}, {$userInfo['email']}</td>
                                    </tr>
                                     <tr>
                                        <td width=\"33%\"><b>Коммерческое предложение действует до</b></td>
                                        <td style=\"text-align: left;\">{$day_valid} {$mes_valid} {$god_valid} г.</td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>";


        $conditions = App::call()->request->getParams()['storage'];
        $turnaround = App::call()->request->getParams()['turnaround'];
        $ru_shipping = App::call()->request->getParams()['ru_shipping'];
        $typeDelivery = App::call()->request->getParams()['typeDelivery'];
        $ru_gmc = App::call()->request->getParams()['ru_gmc'];
        $ru_capacity = App::call()->request->getParams()['ru_capacity'];
        $ru_thermal_mode = App::call()->request->getParams()['ru_thermal_mode'];
        $ru_count = App::call()->request->getParams()['ru_count'];
        $ru_total = App::call()->request->getParams()['ru_total'];
        $currency = App::call()->request->getParams()['currency'];
        $totalPrice = App::call()->request->getParams()['totalPrice'];

        //$totalPrice = $ru_total + $totalPrice;

        if ($turnaround == '') {
            $turnaround_title = '';
        } else {
            $turnaround_title = '<b>Срок выполнения коллекции</b>';
        }

        $table_two = "<br><table width=\"100%\" >
                                    <tbody>
                                    <tr>
                                        <td width=\"33%\">$turnaround_title</td>
                                        <td width=\"38%\" style=\"text-align: right;\"><b>$turnaround</b></td>
                                    </tr>
                                    <tr>
                                        <td width=\"33%\"><b>Условия хранения</b></td>
                                        <td width=\"38%\" style=\"text-align: right;\"><b>$conditions</b></td>
                                    </tr>
                                    </tbody>
                                </table>";
        $newlines_1_text = "";
        $newlines_1 = App::call()->request->getParams()['newlines_1'];
        for ($i = 0; $i < $newlines_1; $i++) {
            $newlines_1_text .= "<br>";
        }
        $newlines_2 = App::call()->request->getParams()['newlines_2'];
        $newlines_2_text = '';
        for ($i = 0; $i < $newlines_2; $i++) {
            $newlines_2_text .= '<br>';
        }
        $table_body = "<table class=\"offer__table\" cellpadding=\"0\" cellspacing=\"0\" width='100%' >
        
                                    <thead>
                                    <tr class=\"xl90\"  style=\"page-break-after:avoid\">
                                        <th>№</th>
                                        <th >{$zabolevanie}</th>
                                        <th>{$opis}</th>
                                        <th>{$kol}</th>
                                        <th>{$cenaEtapa}</th>
                                        <th>{$obstoimost}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                             {$table_body}                          
                    </tbody>
        

        
        </table>";

        if($typeDelivery == 0) {
            $table_three= '';
        }  elseif ($typeDelivery == 1) {

            $table_three = "<br><table width=\"100%\" style='font-family: Calibri,Candara,Segoe,Segoe UI,Optima,Arial,sans-serif; '>
                                    <thead>
                                    <tr class=\"xl90\" style=\"page-break-after:avoid\">
                                        <td><b>Доставка</b></td>
                                        <td><b>Курьер</b></td>
                                        <td><b>Объем контейнера/коробки</b></td>
                                        <td><b>Температурный режим</b></td>
                                        <td><b>Количество отправок</b></td>
                                        <td><b>Общая стоимость услуг</b></td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr class=\"xl91\">
                                        <td>$ru_shipping</td>
                                        <td>$ru_gmc</td>
                                        <td>$ru_capacity</td>
                                        <td>$ru_thermal_mode</td>
                                        <td>$ru_count</td>
                                        <td>$ru_total</td>
                                    </tr>
                                    </tbody>
                                </table>";
        } elseif ($typeDelivery == 2) {
            $table_three = "
            <table width=\"100%\" style='font-family: Calibri,Candara,Segoe,Segoe UI,Optima,Arial,sans-serif; '>
                                    <thead>
                                    <tr>
                                        <td width=\"33%\"><b>Доставка включена в стоимость этапа</b></td>
                                        <td style=\"text-align: left;\"></td>
                                    </tr>
                                    <tbody>
                                    </tbody>
                                </table>
            
            ";
        } elseif ($typeDelivery == 3) {
            $table_three = "
            
            
            <table width=\"100%\" style='font-family: Calibri,Candara,Segoe,Segoe UI,Optima,Arial,sans-serif; '>
                                    <thead>
                                    <tr>
                                        <td width=\"33%\"><b>Доставка осуществляется заказчиком самостоятельно</b></td>
                                        <td style=\"text-align: left;\"></td>
                                    </tr>
                                    <tbody>
                                    </tbody>
                                </table>
            ";

        }



        $footer = "<table width=\"100%\" style=\"margin-top: 25px;\">
                                    <tbody>
                                    <tr>
                                        <td style=\"text-align: right;\">Итого (рублей)</td>
                                        <td class=\"xl90\" style=\"width: 10%;\">$totalPrice</td>
                                    </tr>
                                    
                                    </tbody>
                                </table>
                                <br>
                                <table width=\"100%\" >
                                    <tbody>
                                    <tr>
                                        <td>
                                        <b>От лица ООО \"Национальный БиоСервис\" (ООО \"НБС\")<br>
                                        Олег Гранстрем<br>
                                        Директор отдела развития бизнеса</b>
                                        </td>
                                         <td rowspan=\"1\"  style=\"text-align: left\">
                                            <img src=\"{$server}/app-assets/img/offer/offer_ signature2.png\" width=\"168\" height=\"98\"  alt=\"\" style='border: none'>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>";

        $html = "<html>" . $head . $style . $header . $table_one . $table_body . $table_two . $table_three . $footer . "</html>";
        $dompdf = new Dompdf();

        $html = mb_convert_encoding($html, 'utf-8', mb_detect_encoding($html));
        $dompdf->loadHtml($html, 'UTF-8');

        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $output = $dompdf->output();
        $pdfDate = DATE("d.m.y");
        $version = App::call()->request->getParams()['version'];

        $filename = "QUOTE I-BIOS {$document} {$version} {$pdfDate} RU.pdf";
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

    public function actionSave()
    {
        if (isset(App::call()->request->getParams()['id'])) {
            $id = App::call()->request->getParams()['id'];
            $offer = App::call()->offerRepository->getObject($id);
            if (isset(App::call()->request->getParams()['date_offer'])) {
                $date_offer = App::call()->request->getParams()['date_offer'];
                $offer->date_offer = $date_offer;
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
            if (isset(App::call()->request->getParams()['ru_shipping'])) {
                $ru_shipping = App::call()->request->getParams()['ru_shipping'];
                $offer->ru_shipping = $ru_shipping;
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

            if (isset(App::call()->request->getParams()['ru_gmc'])) {
                $ru_gmc = App::call()->request->getParams()['ru_gmc'];
                $offer->ru_gmc = $ru_gmc;
            }
            if (isset(App::call()->request->getParams()['ru_capacity'])) {
                $ru_capacity = App::call()->request->getParams()['ru_capacity'];
                $offer->ru_capacity = $ru_capacity;
            }
            if (isset(App::call()->request->getParams()['ru_thermal_mode'])) {
                $ru_thermal_mode = App::call()->request->getParams()['ru_thermal_mode'];
                $offer->ru_thermal_mode = $ru_thermal_mode;
            }
            if (isset(App::call()->request->getParams()['ru_count'])) {
                $ru_count = App::call()->request->getParams()['ru_count'];
                $offer->ru_count = $ru_count;
            }
            if (isset(App::call()->request->getParams()['ru_total'])) {
                $ru_total = App::call()->request->getParams()['ru_total'];
                $offer->ru_total = $ru_total;
            }


            if (isset(App::call()->request->getParams()['zabolevanie'])) {
                $zabolevanie = App::call()->request->getParams()['zabolevanie'];
                $offer->zabolevanie = $zabolevanie;
            }

            if (isset(App::call()->request->getParams()['opis'])) {
                $opis = App::call()->request->getParams()['opis'];
                $offer->opis = $opis;
            }

            if (isset(App::call()->request->getParams()['kol'])) {
                $kol = App::call()->request->getParams()['kol'];
                $offer->kol = $kol;
            }

            if (isset(App::call()->request->getParams()['cenaEtapa'])) {
                $cenaEtapa = App::call()->request->getParams()['cenaEtapa'];
                $offer->cenaEtapa = $cenaEtapa;
            }

            if (isset(App::call()->request->getParams()['obstoimost'])) {
                $obstoimost = App::call()->request->getParams()['obstoimost'];
                $offer->obstoimost = $obstoimost;
            }

            if (isset(App::call()->request->getParams()['typeDelivery'])) {
                $typeDelivery = App::call()->request->getParams()['typeDelivery'];
                $offer->typeDelivery = $typeDelivery;
            }

            $result = App::call()->offerRuRepository->save($offer);
            echo $result;
        }
    }

}
