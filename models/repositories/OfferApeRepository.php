<?php


namespace app\models\repositories;

use app\engine\App;
use app\models\entities\OfferApe;
use app\models\Model;
use app\models\Repository;
use app\engine\Db;


class OfferApeRepository extends Repository
{

    public function getTableName()
    {
        return 'nbs_offer_ape';
    }

    public function getEntityClass()
    {
        return OfferApe::class;
    }

    public function getIdName()
    {
        return 'id';
    }


    public function getAll()
    {
        $sql = "SELECT * FROM nbs_offer_ape 
        INNER JOIN nbs_container ON nbs_container.id = nbs_offer_ape.courier_id
        INNER JOIN nbs_users ON  nbs_users.id = nbs_offer_ape.user_id
        INNER JOIN nbs_shipping ON nbs_shipping.id = nbs_offer_ape.shipping_id
        INNER JOIN fr_main_table ON fr_main_table.proj_id = nbs_offer_ape.proj_id
        ";
        return Db::getInstance()->queryAll($sql);
    }


}
