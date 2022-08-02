<?php



namespace app\models\entities;
use app\controllers\SessionController;
use app\models\Model;


class OfferOption extends Model
{
    public $id;
    protected $offer_id;
    protected $newlines_1;
    protected $newlines_2;
    protected $created_at;



    public function __construct(
        $offer_id = null,
        $newlines_1 = null,
        $newlines_2 = null,
        $created_at = null
    )
    {

        $this->offer_id = $offer_id;
        $this->newlines_1 = $newlines_1;
        $this->newlines_2 = $newlines_2;
        $this->created_at = $created_at;

        $this->arrayParams['offer_id'] = false;
        $this->arrayParams['newlines_1'] = false;
        $this->arrayParams['newlines_2'] = false;
        $this->arrayParams['created_at'] = false;
    }

}
