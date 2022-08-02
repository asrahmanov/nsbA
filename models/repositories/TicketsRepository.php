<?php
namespace app\models\repositories;

use app\engine\App;

use app\models\entities\CompaniesContacts;
use app\models\entities\Tickets;
use app\models\Repository;
use app\engine\Db;


class TicketsRepository extends Repository
{

    public function getTableName ()
    {
        return 'nbs_tickets';
    }

    public function getIdName ()
    {
        return 'id';
    }

    public function getEntityClass ()
    {
        return  Tickets::class;
    }

}
