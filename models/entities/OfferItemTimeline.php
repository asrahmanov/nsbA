<?php



namespace app\models\entities;
use app\controllers\SessionController;
use app\models\Model;


class OfferItemTimeline extends Model
{
    public $id;
    protected $offer_item_id;
    protected $timeline;
    protected $samples;
    protected $type_of_collection;
    protected $processing_details;
    protected $price;
    protected $sample_id;
    protected $deleted;
    protected $created_at;

    public function __construct(
        $offer_item_id = null,
        $timeline = null,
        $samples = null,
        $type_of_collection = null,
        $processing_details = null,
        $price = null,
        $sample_id = null,
        $created_at = null,
        $deleted = null
    )
    {
        $this->offer_item_id = $offer_item_id;
        $this->timeline = $timeline;
        $this->samples = $samples;
        $this->type_of_collection = $type_of_collection;
        $this->processing_details = $processing_details;
        $this->price = $price;
        $this->sample_id = $sample_id;
        $this->created_at = $created_at;
        $this->deleted = $deleted;

        $this->arrayParams['offer_item_id'] = false;
        $this->arrayParams['timeline'] = false;
        $this->arrayParams['samples'] = false;
        $this->arrayParams['type_of_collection'] = false;
        $this->arrayParams['processing_details'] = false;
        $this->arrayParams['price'] = false;
        $this->arrayParams['sample_id'] = false;
        $this->arrayParams['created_at'] = false;
        $this->arrayParams['deleted'] = false;
    }

}
