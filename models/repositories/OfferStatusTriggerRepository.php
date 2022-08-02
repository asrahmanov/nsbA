<?php


namespace app\models\repositories;

use app\engine\Db;
use app\models\entities\OfferStatusTrigger;
use app\models\Repository;

class OfferStatusTriggerRepository extends Repository
{

    public function getTableName()
    {
        return 'nbs_offer_status_trigger';
    }

    public function getIdName()
    {
        return 'id';
    }

    public function getEntityClass()
    {
        return OfferStatusTrigger::class;
    }

    public function getAllJoined($date)
    {
        $date_end = "$date 23:59:59";
        $sql = "
            SELECT nost.id as task_id,
                nost.comment, nost.offer_id, DATE(nost.created_at) AS task_date,
                nosa.id ,nosa.created_at AS task_done_date,
                nbs_orders_status.status_name,
                nu.id AS user_id, nu.firstname, nu.lasttname, nu.patronymic,
                fr_main_table.internal_id,
                fr_main_table.external_id,
                fr_scripts.company_name
            FROM
                nbs_offer_status_trigger nost
                LEFT OUTER JOIN nbs_orders_status_action nosa ON (
                    nost.offer_id = nosa.proj_id
                    AND nosa.created_at >= nost.created_at
                    AND nost.status = nosa.orders_status_id)
                LEFT OUTER JOIN nbs_orders_status ON nbs_orders_status.id = nost.status
                JOIN fr_main_table ON nost.offer_id = fr_main_table.proj_id
                JOIN nbs_users nu ON fr_main_table.answering_id = nu.id
                JOIN fr_scripts ON fr_main_table.fr_script_id = fr_scripts.script_id
            WHERE nost.deleted = '0'
                AND nost.created_at <= '$date_end'

                 
            " ;
        return Db::getInstance()->queryAll($sql);
    }


}
