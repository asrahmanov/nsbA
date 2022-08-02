<?php



namespace app\models\entities;
use app\controllers\SessionController;
use app\models\Model;


class OfferItemExclusionCriteria extends Model
{
    public $id;
    protected $offer_item_id;
    protected $exclusion_criteria;
    protected $deleted;
    protected $created_at;



    public function __construct(
        $offer_item_id = null,
        $exclusion_criteria = null,
        $created_at = null,
        $deleted = null
    )
    {

        $this->offer_item_id = $offer_item_id;
        $this->exclusion_criteria = $exclusion_criteria;
        $this->created_at = $created_at;
        $this->deleted = $deleted;

        $this->arrayParams['offer_item_id'] = false;
        $this->arrayParams['exclusion_criteria'] = false;
        $this->arrayParams['created_at'] = false;
        $this->arrayParams['deleted'] = false;

    }


}
