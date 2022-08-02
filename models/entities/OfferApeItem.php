<?php



namespace app\models\entities;
use app\controllers\SessionController;
use app\models\Model;


class OfferApeItem extends Model
{
    public $id;
    protected $offer_id;
    protected $species;
    protected $hbs_type;
    protected $quantity;
    protected $deleted;
    protected $created_at;



    public function __construct(
        $offer_id = null,
        $species = null,
        $hbs_type = null,
        $quantity = 1,
        $created_at = null,
        $deleted = null
    )
    {

        $this->offer_id = $offer_id;
        $this->species = $species;
        $this->hbs_type = $hbs_type;
        $this->quantity = $quantity;
        $this->deleted = $deleted;
        $this->created_at = $created_at;

        $this->arrayParams['offer_id'] = false;
        $this->arrayParams['species'] = false;
        $this->arrayParams['hbs_type'] = false;
        $this->arrayParams['quantity'] = false;
        $this->arrayParams['created_at'] = false;
        $this->arrayParams['deleted'] = false;

    }


}
