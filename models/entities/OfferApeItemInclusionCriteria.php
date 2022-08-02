<?php



namespace app\models\entities;
use app\controllers\SessionController;
use app\models\Model;


class OfferApeItemInclusionCriteria extends Model
{
    public $id;
    protected $offer_ape_item_id;
    protected $inclusion_criteria;
    protected $deleted;
    protected $created_at;



    public function __construct(
        $offer_ape_item_id = null,
        $inclusion_criteria = null,
        $created_at = null,
        $deleted = null
    )
    {

        $this->offer_ape_item_id = $offer_ape_item_id;
        $this->inclusion_criteria = $inclusion_criteria;
        $this->created_at = $created_at;
        $this->deleted = $deleted;

        $this->arrayParams['offer_ape_item_id'] = false;
        $this->arrayParams['inclusion_criteria'] = false;
        $this->arrayParams['created_at'] = false;
        $this->arrayParams['deleted'] = false;

    }


}
