<?php
namespace app\models\repositories;

use app\engine\App;
use app\models\entities\Chat;
use app\models\Model;
use app\models\Repository;
use app\engine\Db;

class ChatRepository extends Repository
{
    public function getTableName()
    {
        return 'nbs_orders_chat';
    }

    public function getEntityClass()
    {
        return Chat::class;
    }

    public function getIdName()
    {
        return 'id';
    }

}
