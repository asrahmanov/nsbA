<?php



namespace app\models\entities;
use app\controllers\SessionController;
use app\models\Model;


class TemplateSitecapability extends Model
{
    public $id;
    protected $name;
    protected $deleted;

    public function __construct(
        $name = null,
        $deleted = null
    )
    {
        $this->name = $name;
        $this->deleted = $deleted;

        $this->arrayParams['name'] = false;
        $this->arrayParams['deleted'] = false;

    }


}
