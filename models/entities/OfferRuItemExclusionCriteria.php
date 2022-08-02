<?php



namespace app\models\entities;
use app\models\Model;


class OfferRuItemExclusionCriteria extends Model
{
    public $id;
    protected $offer_ru_item_id;
    protected $exclusion_criteria;
    protected $deleted;
    protected $created_at;



    public function __construct(
        $offer_ru_item_id = null,
        $exclusion_criteria = null,
        $created_at = null,
        $deleted = null
    )
    {

        $this->offer_ru_item_id = $offer_ru_item_id;
        $this->exclusion_criteria = $exclusion_criteria;
        $this->created_at = $created_at;
        $this->deleted = $deleted;

        $this->arrayParams['offer_ru_item_id'] = false;
        $this->arrayParams['exclusion_criteria'] = false;
        $this->arrayParams['created_at'] = false;
        $this->arrayParams['deleted'] = false;

    }


}
