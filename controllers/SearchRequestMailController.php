<?php

namespace app\controllers;

use app\engine\App;
use app\models\entities\Access;
use app\models\entities\Files;
use app\models\entities\Orders;


class SearchRequestMailController extends Controller
{

    public function actionSearchRequest()
    {
        $imap = imap_open("{imap.mail.ru:993/imap/ssl}INBOX", "newrequest@crm.i-bios.com", "ROIo2aA#pao4");
        $mails_id = imap_search($imap, 'ALL');

        if(!$mails_id){
            die('Писем нет');
        }

//        foreach ($mails_id as $mails_id[0]) {
        $result = 0;
        // Заголовок письма
        $header = imap_header($imap, $mails_id[0]);
        $header = json_decode(json_encode($header), true);
        $email = mb_decode_mimeheader($header['from'][0]['mailbox']) . "@" . mb_decode_mimeheader($header['from'][0]['host']);

        $approve = ['asrahmanov@gmail.com','newrequest@crm.i-bios.com', 'maksim.zvyagintsev@nbioservice.com','sunabak@mail.ru'];

        if (in_array($email, $approve)) {

            $structure = imap_fetchstructure($imap, $mails_id[0]);

            $attachments = array();

            $body = quoted_printable_decode(imap_fetchbody($imap, $mails_id[0], 1));

            $structure = imap_fetchstructure($imap, $mails_id[0]);
//            $body = trim(utf8_encode(quoted_printable_decode($body)));
//            if (isset($structure->parts[1])) {
//                $part = $structure->parts[1];
//                $body = imap_fetchbody($imap,$mails_id[0],1);
//                $body = trim(utf8_encode(quoted_printable_decode($body)));
//                if(strpos($body,"<html") !== false) {
//                    $body = trim(utf8_encode(quoted_printable_decode($body)));
//                } else if ($part->encoding == 6) {
//                    $body = $body;
//                }
//
//                else if ($part->encoding == 3) {
//                    $body = imap_base64($body);
//                }
//                else if($part->encoding == 2) {
//                    $body = imap_binary($body);
//                }
//                else if($part->encoding == 1) {
//                    $body = imap_8bit($body);
//                }
//                else {
//                    $body = trim(utf8_encode(quoted_printable_decode(imap_qprint($body))));
//                }
//            }
            

            if (!stristr($body, 'charset') === FALSE) {
                $body = stristr($body, 'Content-Type: text/html;');
                $body = stristr($body, 'charset');
                $body = str_replace("charset=utf-8", "", $body);
            }

            $order = new Orders();
            $order->fr_date = date("Y-m-d");
            $order->fr_script_id = 315;
            $order->project_details = "Отправитель: {$email}<br>{$body}";

            $result = App::call()->ordersRepository->save($order);
            echo "{$result}<br><br>";

            if ($result > 0) {


                $proj_id = $result;
                // Файлы

                $structure = imap_fetchstructure($imap, $mails_id[0]);

                $attachments = array();

                if(isset($structure->parts) && count($structure->parts))
                {

                    for($i = 0; $i < count($structure->parts); $i++)
                    {

                        $attachments[$i] = array(
                            'is_attachment' => false,
                            'filename' => '',
                            'name' => '',
                            'attachment' => ''
                        );

                        if($structure->parts[$i]->ifdparameters)
                        {

                            foreach($structure->parts[$i]->dparameters as $object)
                            {
                                if(strtolower($object->attribute) == 'filename')
                                {
                                    $attachments[$i]['is_attachment'] = true;
                                    $attachments[$i]['filename'] = $object->value;
                                }
                            }
                        }

                        if($structure->parts[$i]->ifparameters)
                        {
                            foreach($structure->parts[$i]->parameters as $object)
                            {

                                if(strtolower($object->attribute) == 'name')
                                {
                                    $attachments[$i]['is_attachment'] = true;
                                    $attachments[$i]['name'] = $object->value;
                                }
                            }
                        }

                        if($attachments[$i]['is_attachment'])
                        {
                            $attachments[$i]['attachment'] = imap_fetchbody($imap, $mails_id[0], $i+1);

                            /* 4 = QUOTED-PRINTABLE encoding */
                            if($structure->parts[$i]->encoding == 3)

                            {
                                $attachments[$i]['attachment'] = base64_decode($attachments[$i]['attachment']);
                            }
                            /* 3 = BASE64 encoding */
                            elseif($structure->parts[$i]->encoding == 4)
                            {
                                $attachments[$i]['attachment'] = quoted_printable_decode($attachments[$i]['attachment']);
                            }
                        }


                    }
                }

                /* iterate through each attachment and save it */
                foreach($attachments as $attachment)
                {
                    if($attachment['is_attachment'] == 1)
                    {
                        $filename = $attachment['name'];
                        $filename = imap_utf8($filename);
                        if(empty($filename)) $filename = $attachment['filename'];
                        if(empty($filename)) $filename = time() . "dat";


                        $dirname = 'upload/' . $proj_id . '/';

                        if (file_exists($dirname)) {
                            //echo "Директория существует";
                        } else {
                            mkdir($dirname);
                        }

                        $upload = "upload/{$proj_id}/{$filename}";
                        file_put_contents($upload, $attachment['attachment']);
                        set_time_limit(0);
                        $file = new Files();
                        $file->name = $filename;
                        $file->alias = $dirname;
                        $file->proj_id = $proj_id;
                        $file->info = 'clientsend';
                        App::call()->filesRepository->save($file);
                        echo  "Файл успешно загружен";
                    }

                }



//                imap_delete($imap, $mails_id[0]); // Удалить письмо




            }

        } else {
            echo "Ваш email запрещен!<br>";
        }

//        }

        imap_close($imap);
    }


    public function get_part($imap, $uid, $mimetype, $structure = false, $partNumber = false) {
        if (!$structure) {
            $structure = imap_fetchstructure($imap, $uid, FT_UID);
        }
        if ($structure) {
            if ($mimetype == $this->get_mime_type($structure)) {
                if (!$partNumber) {
                    $partNumber = 1;
                }
                $text = imap_fetchbody($imap, $uid, $partNumber, FT_UID);
                switch ($structure->encoding) {
                    case 3: return imap_base64($text);
                    case 4: return imap_qprint($text);
                    default: return $text;
                }
            }
            // multipart
            if ($structure->type == 1) {
                foreach ($structure->parts as $index => $subStruct) {
                    $prefix = "";
                    if ($partNumber) {
                        $prefix = $partNumber . ".";
                    }
                    $data = $this->get_part($imap, $uid, $mimetype, $subStruct,
                        $prefix. ($index + 1));
                    if ($data) {
                        return $data;
                    }
                }
            }
        }


        return false;
    }

    public function get_mime_type($structure) {
        $primaryMimetype = array("TEXT", "MULTIPART", "MESSAGE", "APPLICATION",
            "AUDIO", "IMAGE", "VIDEO", "OTHER");

        if ($structure->subtype) {
            return $primaryMimetype[(int)$structure->type] . "/" . $structure->subtype;
        }
        return "TEXT/PLAIN";
    }

    public function getBody($uid, $imap) {
        $body = $this->get_part($imap, $uid, "TEXT/HTML");
// if HTML body is empty, try getting text body
        if ($body == "") {
            $body = $this->get_part($imap, $uid, "TEXT/PLAIN");
        }
        return $body;
    }

}
