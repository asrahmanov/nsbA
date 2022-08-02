<?php


namespace app\models\repositories;

use app\engine\App;
use app\models\entities\Offer;
use app\models\Model;
use app\models\Repository;
use app\engine\Db;


class OfferRepository extends Repository
{

    public function getTableName()
    {
        return 'nbs_offer';
    }

    public function getEntityClass()
    {
        return Offer::class;
    }

    public function getIdName()
    {
        return 'id';
    }


    public function getAll()
    {
        $tableName = $this->getTableName();
        $sql = "SELECT * FROM nbs_offer, nbs_offer.id AS id
        INNER JOIN nbs_container ON nbs_container.id = nbs_offer.courier_id
        INNER JOIN nbs_users ON  nbs_users.id = nbs_offer.user_id
        INNER JOIN nbs_shipping ON nbs_shipping.id = nbs_offer.shipping_id
        INNER JOIN fr_main_table ON fr_main_table.proj_id = nbs_offer.proj_id
        ";
        return Db::getInstance()->queryAll($sql);
    }

    public function getAllTrueId()
    {
        $tableName = $this->getTableName();
        $sql = "SELECT * FROM nbs_offer
        ";
        return Db::getInstance()->queryAll($sql);
    }


    public function chechDate()
    {
        $sql = "SELECT * FROM nbs_offer 
        WHERE nbs_offer.date_offer = nbs_offer.date_valid
        ";
        return Db::getInstance()->queryAll($sql);
    }




}
