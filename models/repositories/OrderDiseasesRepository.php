<?php

namespace app\models\repositories;

use app\engine\Db;
use app\models\entities\OrderDiseases;
use app\models\Repository;

class OrderDiseasesRepository extends Repository
{

    public function getTableName()
    {
        return 'nbs_orders_diseases';
    }

    public function getIdName()
    {
        return 'id';
    }

    public function getEntityClass()
    {
        return OrderDiseases::class;
    }

    public function GetDiseaseByOrder($proj_id){
        $sql = "
            SELECT *, nbs_orders_diseases.id as id FROM nbs_orders_diseases
            INNER JOIN nbs_disease ON nbs_disease.id = nbs_orders_diseases.disease_id
            WHERE  nbs_orders_diseases.order_id =:order_id    
            " ;

        $params = [
            'order_id' => $proj_id
        ];

        return Db::getInstance()->queryAll($sql, $params);
    }


}
