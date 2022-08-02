<?php

namespace app\models\repositories;

use app\models\entities\NewTicketRating;
use app\models\Repository;


class NewTicketRatingRepository extends Repository
{

    public function getTableName ()
    {
        return 'nbs_new_ticket_rating';
    }

    public function getIdName ()
    {
        return 'id';
    }

    public function getEntityClass ()
    {
        return  NewTicketRating::class;
    }

}
