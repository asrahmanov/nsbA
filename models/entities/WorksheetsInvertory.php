<?php

namespace app\models\entities;

use app\controllers\SessionController;
use app\models\Model;


class WorksheetsInvertory extends Model
{
    public $id;
    protected $proj_id;
    protected $user_id;
    protected $sample;
    protected $comments;
    protected $alias;
    protected $created_at;
    protected $deleted;

    public function __construct(
        $proj_id = null,
        $user_id = null,
        $sample = null,
        $alias = null,
        $created_at = null,
        $comments = null,
        $deleted = 0
    )
    {
        $this->proj_id = $proj_id;
        $this->user_id = $user_id;
        $this->sample = $sample;
        $this->alias = $alias;
        $this->created_at = $created_at;
        $this->deleted = $deleted;
        $this->comments = $comments;

        $this->arrayParams['proj_id'] = false;
        $this->arrayParams['user_id'] = false;
        $this->arrayParams['sample'] = false;
        $this->arrayParams['alias'] = false;
        $this->arrayParams['created_at'] = false;
        $this->arrayParams['deleted'] = false;
        $this->arrayParams['comments'] = false;

    }


}
