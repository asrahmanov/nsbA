<?php


namespace app\models\repositories;

use app\engine\App;
use app\models\entities\Mail;
use app\models\Repository;

use app\engine\Db;


class MailRepository extends Repository
{

    public function getTableName()
    {
        return 'nbs_mail';
    }

    public function getEntityClass()
    {
        return Mail::class;
    }

    public function getIdName()
    {
        return 'id';
    }


    public function sendMail($email, $subject, $text, $action = 'no', $proj_id = '0', $send_time = '')
    {
        $mailSend = new Mail();

        if (($email != '') && !is_null($email)) {

            if($send_time == '') {
                $send_time = Date('Y-m-d H:i:s');
            }

            $mailSend->email = $email;
            $mailSend->subject = $subject;
            $mailSend->text_mail = $text;
            $mailSend->send_time = $send_time;
            $mailSend->send = 'NO';
            $mailSend->action = $action;
            $mailSend->proj_id = $proj_id;

        }
        return $this->save($mailSend);
    }

    public function sendToDepartament($department_id, $subject, $text)
    {
        $users = App::call()->usersRepository->getUsersByDepartemant($department_id);
        for ($i = 0; $i < count($users); $i++) {
            $email = $users[$i]['email'];
            if ($email != '') {
                $this->sendMail($email, $subject, $text);
            }
        }
    }


}
