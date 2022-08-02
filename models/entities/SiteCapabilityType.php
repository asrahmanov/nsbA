<?php



namespace app\models\entities;
use app\controllers\SessionController;
use app\models\Model;


class SiteCapabilityType extends Model
{
    public $id;
    protected $template_id;
    protected $diseases_id;
    protected $tissues_id;
    protected $typevalue;
    protected $placeholdervalue;
    protected $deleted;

    public function __construct(
        $template_id = null,
        $diseases_id = null,
        $tissues_id= null,
        $typevalue = null,
        $placeholdervalue = null,
        $deleted = null
    )
    {
        $this->template_id = $template_id;
        $this->diseases_id  = $diseases_id;
        $this->tissues_id  = $tissues_id;
        $this->typevalue  = $typevalue;
        $this->placeholdervalue  = $placeholdervalue;
        $this->deleted  = $deleted;


        $this->arrayParams['template_id'] = false;
        $this->arrayParams['diseases_id'] = false;
        $this->arrayParams['tissues_id'] = false;
        $this->arrayParams['typevalue'] = false;
        $this->arrayParams['placeholdervalue'] = false;
        $this->arrayParams['deleted'] = false;

    }


}
