<?php



namespace app\models\entities;
use app\controllers\SessionController;
use app\models\Model;


class OfferApproval extends Model
{
    public $id;
    protected $offer_id;
    protected $user_id;
    protected $approved;
    protected $comment;
    protected $created_at;
    protected $deleted;

    public function __construct(
        $offer_id = null,
        $user_id = null,
        $approved = null,
        $comment = null,
        $created_at = null,
        $deleted = null
    )
    {
        $this->offer_id = $offer_id;
        $this->user_id = $user_id;
        $this->approved = $approved;
        $this->comment = $comment;
        $this->created_at = $created_at;
        $this->deleted = $deleted;

        $this->arrayParams['offer_id'] = false;
        $this->arrayParams['user_id'] = false;
        $this->arrayParams['approved'] = false;
        $this->arrayParams['comment'] = false;
        $this->arrayParams['created_at'] = false;
        $this->arrayParams['deleted'] = false;
    }

}
