<?php



namespace app\models\entities;
use app\controllers\SessionController;
use app\models\Model;


class TypeScript extends Model
{
    public $id;
    protected $type_name;
    protected $deleted;


    public function __construct(
        $type_name = null,
        $deleted = null
    )
    {
        $this->type_name = $type_name;
        $this->deleted = $deleted;

        $this->arrayParams['type_name'] = false;
        $this->arrayParams['deleted'] = false;

    }





}
