<?php

namespace app\models\repositories;

use app\engine\Db;
use app\models\entities\DiseasesBiospecimenTypes;
use app\models\Repository;

class DiseasesBiospecimenTypesRepository extends Repository
{

    public function getTableName()
    {
        return 'nbs_diseases_biospecimen_types';
    }

    public function getIdName()
    {
        return 'id';
    }

    public function getEntityClass()
    {
        return DiseasesBiospecimenTypes::class;
    }


    public function GetdiseasesBiospecimenTypesRepository($order_id)
    {

        $tableName = $this->getTableName();
        $sql = "SELECT *, nbs_diseases_biospecimen_types.id as id FROM nbs_diseases_biospecimen_types
        INNER JOIN  nbs_disease ON nbs_disease.id = nbs_diseases_biospecimen_types.disease_id
        INNER JOIN nbs_biospecimen_type ON nbs_biospecimen_type.id = nbs_diseases_biospecimen_types.biospecimen_type_id
        WHERE nbs_diseases_biospecimen_types.order_id =:order_id
        ";

        $params = [
            'order_id' => $order_id
        ];
        return Db::getInstance()->queryAll($sql, $params);
    }

    public function actionGetALlBiospecimenTypesByOrderLeft($order_id)
    {

        $tableName = $this->getTableName();
        $sql = "SELECT *, nbs_diseases_biospecimen_types.id as nbs_diseases_biospecimen_types_id FROM  nbs_orders_diseases
                left outer JOIN  nbs_diseases_biospecimen_types
                ON nbs_orders_diseases.order_id = nbs_diseases_biospecimen_types.order_id
                and nbs_orders_diseases.disease_id = nbs_diseases_biospecimen_types.disease_id
                INNER JOIN nbs_disease ON nbs_disease.id = nbs_orders_diseases.disease_id        
                LEFT JOIN nbs_biospecimen_type ON nbs_diseases_biospecimen_types.biospecimen_type_id = nbs_biospecimen_type.id 
                LEFT JOIN nbs_sample_mods ON nbs_sample_mods.id = nbs_diseases_biospecimen_types.mod_id 
                WHERE nbs_orders_diseases.order_id =:order_id
        ";

        $params = [
            'order_id' => $order_id
        ];
        return Db::getInstance()->queryAll($sql, $params);
    }

}
