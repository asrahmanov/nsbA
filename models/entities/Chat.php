<?php



namespace app\models\entities;
use app\controllers\SessionController;
use app\models\Model;


class Chat extends Model
{
    public $id;
    protected $proj_id;
    protected $message;
    protected $sender;
    protected $viewed;
    protected $deleted;

    public function __construct(
        $proj_id = null,
        $message = null,
        $sender = null,
        $viewed = null,
        $deleted = null
    )
    {
        $this->proj_id = $proj_id;
        $this->message = $message;
        $this->sender = $sender;
        $this->viewed = $viewed;
        $this->deleted = $deleted;

        $this->arrayParams['proj_id'] = false;
        $this->arrayParams['message'] = false;
        $this->arrayParams['sender'] = false;
        $this->arrayParams['viewed'] = false;
        $this->arrayParams['deleted'] = false;
    }


}
