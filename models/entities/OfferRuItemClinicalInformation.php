<?php



namespace app\models\entities;
use app\models\Model;


class OfferRuItemClinicalInformation extends Model
{
    public $id;
    protected $offer_ru_item_id;
    protected $clinical_information;
    protected $deleted;
    protected $created_at;



    public function __construct(
        $offer_ru_item_id = null,
        $clinical_information = null,
        $created_at = null,
        $deleted = null
    )
    {

        $this->offer_ru_item_id = $offer_ru_item_id;
        $this->clinical_information = $clinical_information;
        $this->created_at = $created_at;
        $this->deleted = $deleted;

        $this->arrayParams['offer_ru_item_id'] = false;
        $this->arrayParams['clinical_information'] = false;
        $this->arrayParams['created_at'] = false;
        $this->arrayParams['deleted'] = false;

    }


}
