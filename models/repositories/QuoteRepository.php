<?php


namespace app\models\repositories;

use app\engine\App;
use app\models\entities\Quote;


use app\models\Repository;
use app\engine\Db;

class QuoteRepository extends Repository
{

    public function getEntityClass()
    {
        return Quote::class;
    }


    public function getIdName()
    {
        return 'id';
    }

    public function getTableName()
    {
        return 'nbs_quote';
    }


    public function getUsersQuotebyProjId($user_id, $proj_id) {
        $sql = "SELECT *
        FROM nbs_quote 
        INNER JOIN fr_sites ON fr_sites.site_id = nbs_quote.site_id
        WHERE  nbs_quote.user_id=:user_id
        AND nbs_quote.proj_id=:proj_id
        ";
        $params = [
            'proj_id' => $proj_id,
            'user_id' => $user_id
        ];
        return  Db::getInstance()->queryAll($sql, $params);

    }


    public function getQuotebyProj($proj_id){
        $sql = "SELECT *, nbs_quote.id AS id, nbs_quote.created_at AS created_at
        FROM nbs_quote 
        INNER JOIN fr_sites ON fr_sites.site_id = nbs_quote.site_id
        INNER JOIN nbs_users ON nbs_users.id = nbs_quote.user_id
        WHERE  nbs_quote.proj_id=:proj_id
        ";
        $params = [
            'proj_id' => $proj_id
        ];
        return  Db::getInstance()->queryAll($sql, $params);
    }

    public function getHistoryQuotesByDiseaseAndSampleIds ($order_disease, $diseases_biospecimen_type) {
        $sql = "
        SELECT *,
            nbs_quote.id AS id, nbs_quote.created_at AS created_at
        FROM nbs_quote
            INNER JOIN nbs_quotes_samples ON nbs_quotes_samples.quote_id = nbs_quote.id
            INNER JOIN fr_sites ON fr_sites.site_id = nbs_quote.site_id
            INNER JOIN nbs_users ON nbs_users.id = nbs_quote.user_id
        WHERE
            nbs_quotes_samples.disease_id =:order_disease
            AND nbs_quotes_samples.biospecimen_type_id =:diseases_biospecimen_type
        ";
        $params = [
            'order_disease' => $order_disease,
            'diseases_biospecimen_type' => $diseases_biospecimen_type,
        ];
        return  Db::getInstance()->queryAll($sql, $params);
    }


    public function getHistoryQuotesByDiseaseAndSampleIdsAndUser ($order_disease, $diseases_biospecimen_type, $user_id) {
        $sql = "
        SELECT *,
            nbs_quote.id AS id, nbs_quote.created_at AS created_at
        FROM nbs_quote
            INNER JOIN nbs_quotes_samples ON nbs_quotes_samples.quote_id = nbs_quote.id
            INNER JOIN fr_sites ON fr_sites.site_id = nbs_quote.site_id
            INNER JOIN nbs_users ON nbs_users.id = nbs_quote.user_id
        WHERE
            nbs_quotes_samples.disease_id =:order_disease
            AND nbs_quotes_samples.biospecimen_type_id =:diseases_biospecimen_type
            AND nbs_quote.user_id =:user_id
        ";
        $params = [
            'order_disease' => $order_disease,
            'diseases_biospecimen_type' => $diseases_biospecimen_type,
            'user_id' => $user_id
        ];
        return  Db::getInstance()->queryAll($sql, $params);
    }

    public function getMaxValue() {
        $sql = "SELECT proj_id, SUM(value_mount) as total
        FROM nbs_quote 
        GROUP by proj_id
        ";
        return  Db::getInstance()->queryAll($sql);
    }

}
