<?php



namespace app\models\entities;
use app\controllers\SessionController;
use app\models\Model;


class Pages extends Model
{
    public $id;
    protected $name;
    protected $alias;
    protected $template;
    protected $deleted;

    public function __construct(
        $name = null,
        $alias = null,
        $template = null,
        $deleted = null
    )
    {
        $this->name = $name;
        $this->alias = $alias;
        $this->template = $template;
        $this->deleted = $deleted;

        $this->arrayParams['name'] = false;
        $this->arrayParams['alias'] = false;
        $this->arrayParams['template'] = false;
        $this->arrayParams['deleted'] = false;

    }


}
