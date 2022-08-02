<?php



namespace app\models\entities;
use app\controllers\SessionController;
use app\models\Model;


class Mail extends Model
{
    public $id;
    protected $email;
    protected $subject;
    protected $text_mail;
    protected $send_time;
    protected $send;
    protected $reply_to;
    protected $action;
    protected $proj_id;




    public function __construct(
        $email = null,
        $subject = null,
        $text_mail = null,
        $send_time = null,
        $reply_to = null,
        $proj_id = 0,
        $send = 'NOT',
        $action = 'no'
    )
    {
        $this->email = $email;
        $this->subject = $subject;
        $this->text_mail = $text_mail;
        $this->send_time = $send_time;
        $this->send = $send;
        $this->reply_to = $reply_to;
        $this->action = $action;
        $this->proj_id = $proj_id;

        $this->arrayParams['email'] = false;
        $this->arrayParams['subject'] = false;
        $this->arrayParams['text_mail'] = false;
        $this->arrayParams['send_time'] = false;
        $this->arrayParams['send'] = false;
        $this->arrayParams['reply_to'] = false;
        $this->arrayParams['action'] = false;
        $this->arrayParams['proj_id'] = false;

    }


}
