<?php



namespace app\models\entities;
use app\controllers\SessionController;
use app\models\Model;


class OfferItem extends Model
{
    public $id;
    protected $offer_id;
    protected $disease;
    protected $hbs_type;
    protected $quantity;
    protected $specific_requirements;
    protected $ethnicity;
    protected $disease_id;
    protected $sample_id;
    protected $deleted;
    protected $created_at;
    protected $turnaround_time;



    public function __construct(
        $offer_id = null,
        $disease = null,
        $hbs_type = null,
        $quantity = 1,
        $created_at = null,
        $deleted = null,
        $specific_requirements = null,
        $ethnicity = null,
        $disease_id = null,
        $sample_id = null,
        $turnaround_time = null
    )
    {

        $this->offer_id = $offer_id;
        $this->disease = $disease;
        $this->hbs_type = $hbs_type;
        $this->quantity = $quantity;
        $this->deleted = $deleted;
        $this->specific_requirements = $specific_requirements;
        $this->ethnicity = $ethnicity;
        $this->created_at = $created_at;
        $this->disease_id = $disease_id;
        $this->sample_id = $sample_id;
        $this->turnaround_time = $turnaround_time;

        $this->arrayParams['offer_id'] = false;
        $this->arrayParams['disease'] = false;
        $this->arrayParams['hbs_type'] = false;
        $this->arrayParams['quantity'] = false;
        $this->arrayParams['created_at'] = false;
        $this->arrayParams['deleted'] = false;
        $this->arrayParams['specific_requirements'] = false;
        $this->arrayParams['ethnicity'] = false;
        $this->arrayParams['disease_id'] = false;
        $this->arrayParams['sample_id'] = false;
        $this->arrayParams['turnaround_time'] = false;

    }


}
