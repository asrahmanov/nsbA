<?php

namespace app\models\entities;
use app\models\Model;

class OfferStatusTrigger extends Model
{
    public $id;
    protected $offer_id;
    protected $status;
    protected $comment;
    protected $created_at;



    public function __construct(
        $offer_id = null,
        $status = null,
        $created_at = null,
        $comment = null
    )
    {

        $this->offer_id = $offer_id;
        $this->status = $status;
        $this->comment = $comment;
        $this->created_at = $created_at;

        $this->arrayParams['offer_id'] = false;
        $this->arrayParams['status'] = false;
        $this->arrayParams['comment'] = false;
        $this->arrayParams['created_at'] = false;

    }

}
