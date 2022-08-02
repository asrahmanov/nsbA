<?php



namespace app\models\entities;
use app\controllers\SessionController;
use app\models\Model;


class StorageConditions extends Model
{
    public $id;
    protected $storage_conditions;
    protected $deleted;
    protected $created_at;

    public function __construct(
        $storage_conditions = null,
        $created_at = null,
        $deleted = null
    )
    {
        $this->storage_conditions = $storage_conditions;

        $this->arrayParams['storage_conditions'] = false;
    }

}
