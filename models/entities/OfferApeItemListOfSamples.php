<?php



namespace app\models\entities;
use app\controllers\SessionController;
use app\models\Model;


class OfferApeItemListOfSamples extends Model
{
    public $id;
    protected $offer_ape_item_id;
    protected $list_of_samples;
    protected $deleted;
    protected $created_at;

    public function __construct(
        $offer_ape_item_id = null,
        $list_of_samples = null,
        $created_at = null,
        $deleted = null
    )
    {

        $this->offer_ape_item_id = $offer_ape_item_id;
        $this->list_of_samples = $list_of_samples;
        $this->created_at = $created_at;
        $this->deleted = $deleted;

        $this->arrayParams['offer_ape_item_id'] = false;
        $this->arrayParams['list_of_samples'] = false;
        $this->arrayParams['created_at'] = false;
        $this->arrayParams['deleted'] = false;

    }

}
