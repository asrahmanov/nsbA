<?php



namespace app\models\entities;
use app\controllers\SessionController;
use app\models\Model;


class BiospecimenType extends Model
{
    public $id;
    protected $biospecimen_type;
    protected $biospecimen_type_russian;
    protected $deleted;
    protected $created_at;

    public function __construct(
        $biospecimen_type = null,
        $biospecimen_type_russian = null
    )
    {
        $this->biospecimen_type = $biospecimen_type;
        $this->biospecimen_type_russian = $biospecimen_type_russian;

        $this->arrayParams['biospecimen_type'] = false;
        $this->arrayParams['biospecimen_type_russian'] = false;
    }

}
