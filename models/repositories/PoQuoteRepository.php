<?php


namespace app\models\repositories;

use app\engine\App;
use app\models\entities\PoQuote;
use app\models\entities\Quote;


use app\models\Repository;
use app\engine\Db;

class PoQuoteRepository extends Repository
{

    public function getEntityClass()
    {
        return PoQuote::class;
    }


    public function getIdName()
    {
        return 'id';
    }

    public function getTableName()
    {
        return 'nbs_po_quote';
    }


    public function getUsersQuotebyProjId($user_id, $proj_id) {
        $sql = "SELECT *
        FROM nbs_po_quote 
        INNER JOIN fr_sites ON fr_sites.site_id = nbs_po_quote.site_id
        WHERE  nbs_po_quote.user_id=:user_id
        AND nbs_po_quote.proj_id=:proj_id
        ";
        $params = [
            'proj_id' => $proj_id,
            'user_id' => $user_id
        ];
        return  Db::getInstance()->queryAll($sql, $params);

    }


    public function getQuotebyProj($proj_id){
        $sql = "SELECT *, nbs_po_quote.id AS id, nbs_po_quote.created_at AS created_at, nbs_po_quote.user_id AS user_id 
        FROM nbs_po_quote 
        INNER JOIN fr_sites ON fr_sites.site_id = nbs_po_quote.site_id
        INNER JOIN nbs_users ON nbs_users.id = nbs_po_quote.user_id
        WHERE  nbs_po_quote.proj_id=:proj_id
        ";
        $params = [
            'proj_id' => $proj_id
        ];

        return  Db::getInstance()->queryAll($sql, $params);
    }


    public function getHistoryQuotesByDiseaseAndSampleIds ($order_disease, $diseases_biospecimen_type) {
        $sql = "
        SELECT *,
            nbs_po_quote.id AS id, nbs_po_quote.created_at AS created_at
        FROM nbs_po_quote
            INNER JOIN nbs_po_quotes_samples ON nbs_po_quotes_samples.quote_id = nbs_po_quote.id
            INNER JOIN fr_sites ON fr_sites.site_id = nbs_po_quote.site_id
            INNER JOIN nbs_users ON nbs_users.id = nbs_po_quote.user_id
        WHERE
            nbs_po_quotes_samples.disease_id =:order_disease
            AND nbs_po_quotes_samples.biospecimen_type_id =:diseases_biospecimen_type
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
            nbs_po_quote.id AS id, nbs_po_quote.created_at AS created_at
        FROM nbs_po_quote
            INNER JOIN nbs_po_quotes_samples ON nbs_po_quotes_samples.quote_id = nbs_po_quote.id
            INNER JOIN fr_sites ON fr_sites.site_id = nbs_po_quote.site_id
            INNER JOIN nbs_users ON nbs_users.id = nbs_po_quote.user_id
        WHERE
            nbs_po_quotes_samples.disease_id =:order_disease
            AND nbs_po_quotes_samples.biospecimen_type_id =:diseases_biospecimen_type
            AND nbs_po_quote.user_id =:user_id
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
        FROM nbs_po_quote 
        GROUP by proj_id
        ";
        return  Db::getInstance()->queryAll($sql);
    }

    public function getSample($quote_id) {
        $sql = "SELECT nbs_biospecimen_type.biospecimen_type, nbs_biospecimen_type.biospecimen_type_russian
        FROM nbs_po_quote
        INNER JOIN nbs_po_quotes_samples ON nbs_po_quotes_samples.quote_id = nbs_po_quote.id   
        INNER JOIN nbs_biospecimen_type ON nbs_biospecimen_type.id = nbs_po_quotes_samples.biospecimen_type_id
        WHERE nbs_po_quote.id = :quote_id
        ";

        $params = [
            'quote_id' => $quote_id
        ];

        return  Db::getInstance()->queryAll($sql, $params);
    }

}
