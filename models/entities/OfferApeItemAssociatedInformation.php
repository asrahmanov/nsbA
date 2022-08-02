<?php



namespace app\models\entities;
use app\controllers\SessionController;
use app\models\Model;


class OfferApeItemAssociatedInformation extends Model
{
    public $id;
    protected $offer_ape_item_id;
    protected $associated_information;
    protected $deleted;
    protected $created_at;



    public function __construct(
        $offer_ape_item_id = null,
        $associated_information = null,
        $created_at = null,
        $deleted = null
    )
    {

        $this->offer_ape_item_id = $offer_ape_item_id;
        $this->associated_information = $associated_information;
        $this->created_at = $created_at;
        $this->deleted = $deleted;

        $this->arrayParams['offer_ape_item_id'] = false;
        $this->arrayParams['associated_information'] = false;
        $this->arrayParams['created_at'] = false;
        $this->arrayParams['deleted'] = false;

    }


}
