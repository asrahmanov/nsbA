<?php



namespace app\models\entities;
use app\controllers\SessionController;
use app\models\Model;


class Cities extends Model
{
    public $city_id;
    protected $city_name;

    public function __construct(
        $city_name = null
    )
    {
        $this->city_name = $city_name;

        $this->arrayParams['city_name'] = false;
    }


}
