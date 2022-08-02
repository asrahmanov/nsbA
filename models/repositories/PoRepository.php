<?php


namespace app\models\repositories;

use app\engine\App;
use app\models\entities\Po;
use app\models\Repository;
use app\engine\Db;


class PoRepository extends Repository
{

    public function getTableName()
    {
        return 'po_main_table';
    }

    public function getEntityClass()
    {
        return Po::class;
    }

    public function getIdName()
    {
        return 'id';
    }


    public function getOrdersMy()
    {

        $user_id = App::call()->session->getSession('user_id');
        $tableName = $this->getTableName();
//        // App::call()->session->checkSessionAndDestroy();
        $sql = "SELECT * FROM {$tableName} WHERE answering_id=:answering_id ORDER by priority_id DESC";
        $params = ['answering_id' => $user_id];
        return Db::getInstance()->queryAll($sql, $params);
    }


    public function GetOrdersOne($proj_id)
    {
        $tableName = $this->getTableName();
//        // App::call()->session->checkSessionAndDestroy();
        $sql = "SELECT * FROM {$tableName} WHERE proj_id=:proj_id  ";
        $params = ['proj_id' => $proj_id];
        $result = Db::getInstance()->queryOne($sql, $params);
        return $result;
    }


    public function maxScriptNum($fr_script_id)
    {
        $tableName = $this->getTableName();

//        // App::call()->session->checkSessionAndDestroy();
        $sql = "SELECT MAX(script_number) as script_number FROM {$tableName} WHERE  fr_script_id=:fr_script_id ";
        $params = ['fr_script_id' => $fr_script_id];
        $result = Db::getInstance()->queryOne($sql, $params);
        return $result;
    }


    public function getAllStatus($fr_status)
    {
        $tableName = $this->getTableName();
//        // App::call()->session->checkSessionAndDestroy();
        $sql = "SELECT *  FROM {$tableName} WHERE  fr_status=:fr_status ";
        $params = ['fr_status' => $fr_status];
        $result = Db::getInstance()->queryAll($sql, $params);
        return $result;

    }

    public function getManagerAll()
    {
        $tableName = $this->getTableName();
        // App::call()->session->checkSessionAndDestroy();
        $sql = "SELECT *  FROM {$tableName} WHERE  
        status_manager=2 OR
        status_manager=3
         ";
        $result = Db::getInstance()->queryAll($sql);
        return $result;

    }


    public function getOrderByDesc()
    {
        $tableName = $this->getTableName();
        // App::call()->session->checkSessionAndDestroy();
        $sql = "SELECT *  FROM {$tableName}
        ORDER by proj_id DESC";
        $result = Db::getInstance()->queryAll($sql);
        return $result;

    }


    public function getOrdersMyPriority()
    {
        $user_id = App::call()->session->getSession('user_id');
        $tableName = $this->getTableName();
        // App::call()->session->checkSessionAndDestroy();
        $sql = "SELECT COUNT(proj_id) AS total FROM {$tableName} WHERE answering_id=:answering_id 
        AND priority_id=:priority_id
        ORDER by proj_id DESC";
        $params = ['answering_id' => $user_id,
            'priority_id' => 1];
        return Db::getInstance()->queryOne($sql, $params);
    }


    public function getWhereOrder($columsAndParams, $order, $sort)
    {
        $tableName = $this->getTableName();
        $columsNames = '';
        $params = [];
        foreach ($columsAndParams as $key => $value) {
            $columsNames .= $key . "=:{$key} AND ";
            $params[$key] = $value;
        }
        $columsNames = substr($columsNames, 0, -5);
        $sql = "SELECT * FROM {$tableName} WHERE {$columsNames}  ORDER by {$order} {$sort}";
        return Db::getInstance()->queryAll($sql, $params);
    }


    public function getAllbyDateDesc($frDateOne, $frDateTwo)
    {
        $sql = "SELECT * FROM po_main_table 
        WHERE (po_main_table.fr_date >=:frDateOne 
        AND po_main_table.fr_date <=:frDateTwo)
        OR po_main_table.fr_date IS NULL
        ORDER by po_main_table.proj_id DESC
        ";
        $params = [
            'frDateOne' => $frDateOne,
            'frDateTwo' => $frDateTwo
        ];

        return Db::getInstance()->queryAll($sql, $params);

    }

    public function getAllbyDate($frDateOne, $frDateTwo)
    {
        $sql = "SELECT * FROM po_main_table 
        INNER JOIN nbs_users ON nbs_users.id =  po_main_table.answering_id 
        INNER JOIN fr_scripts ON fr_scripts.script_id =  po_main_table.fr_script_id 
        INNER JOIN fr_status_values ON fr_status_values.fr_status_id =  po_main_table.fr_status 
        WHERE (po_main_table.fr_date >=:frDateOne 
        AND po_main_table.fr_date <=:frDateTwo) OR po_main_table.fr_date IS NULL
        ";
        $params = [
            'frDateOne' => $frDateOne,
            'frDateTwo' => $frDateTwo
        ];

        return Db::getInstance()->queryAll($sql, $params);

    }

    public function getAllbyDateMin($frDateOne, $frDateTwo)
    {
        $sql = "SELECT * FROM po_main_table 
        WHERE (po_main_table.fr_date >=:frDateOne 
        AND po_main_table.fr_date <=:frDateTwo) OR po_main_table.fr_date IS NULL
        ";
        $params = [
            'frDateOne' => $frDateOne,
            'frDateTwo' => $frDateTwo
        ];

        return Db::getInstance()->queryAll($sql, $params);

    }


    public function getAllbyDateDASHBORD($frDateOne, $frDateTwo)
    {
        $sql = "SELECT *, (SELECT COUNT(*) FROM nbs_quote WHERE nbs_quote.proj_id = po_main_table.proj_id ) as countQuote FROM po_main_table 
        INNER JOIN nbs_users ON nbs_users.id =  po_main_table.answering_id 
        INNER JOIN fr_scripts ON fr_scripts.script_id =  po_main_table.fr_script_id 
        INNER JOIN fr_status_values ON fr_status_values.fr_status_id =  po_main_table.fr_status 
        WHERE po_main_table.fr_date >=:frDateOne
        AND po_main_table.fr_date <=:frDateTwo
        -- AND po_main_table.fr_status NOT IN (3,4,5,6)
        AND po_main_table.status_client NOT IN (27,28,29,33)    
         -- ORDER by
        
        ";
        $params = [
            'frDateOne' => $frDateOne,
            'frDateTwo' => $frDateTwo
        ];
        return Db::getInstance()->queryAll($sql, $params);
    }


    public function getAllbyDateDASHBORDOne($proj_id)
    {
        $sql = "SELECT * FROM po_main_table 
        INNER JOIN nbs_users ON nbs_users.id =  po_main_table.answering_id 
        INNER JOIN fr_scripts ON fr_scripts.script_id =  po_main_table.fr_script_id 
        INNER JOIN fr_status_values ON fr_status_values.fr_status_id =  po_main_table.fr_status 
        WHERE po_main_table.proj_id >=:proj_id
        ";
        $params = [
            'proj_id' => $proj_id
        ];
        return Db::getInstance()->queryOne($sql, $params);
    }


    public function getAllbyDateDASHBORDCompanyPriority($frDateOne, $frDateTwo)
    {
        $sql = "SELECT * FROM po_main_table 
        INNER JOIN nbs_users ON nbs_users.id =  po_main_table.answering_id 
        INNER JOIN fr_scripts ON fr_scripts.script_id =  po_main_table.fr_script_id 
        INNER JOIN fr_status_values ON fr_status_values.fr_status_id =  po_main_table.fr_status 
        WHERE po_main_table.fr_date >=:frDateOne 
        AND po_main_table.fr_date <=:frDateTwo
        -- AND po_main_table.fr_status NOT IN (3,4,5,6)
        AND po_main_table.status_client NOT IN (27,28,29,33)
        AND fr_scripts.priority > 0
        ORDER by fr_scripts.priority ASC
        ";
        $params = [
            'frDateOne' => $frDateOne,
            'frDateTwo' => $frDateTwo
        ];
        return Db::getInstance()->queryAll($sql, $params);
    }

    public function getStaticAll($frDateOne)
    {
        $sql = "SELECT nbs_users.id, COUNT(*) as total, CONCAT(nbs_users.lasttname, ' ', nbs_users.firstname) as fio FROM po_main_table 
        INNER JOIN nbs_users ON nbs_users.id =  po_main_table.answering_id 
        INNER JOIN fr_scripts ON fr_scripts.script_id =  po_main_table.fr_script_id 
        INNER JOIN fr_status_values ON fr_status_values.fr_status_id =  po_main_table.fr_status 
        WHERE po_main_table.fr_date >=:frDateOne 
        -- AND po_main_table.fr_status NOT IN (3,4,5,6)
        AND po_main_table.status_client NOT IN (27,28,29,33)
        GROUP BY nbs_users.id
        ";
        $params = [
            'frDateOne' => $frDateOne,
        ];
        return Db::getInstance()->queryAll($sql, $params);
    }


    public function getStaticGood($frDateOne)
    {
        $sql = "SELECT nbs_users.id, COUNT(*) as total, CONCAT(nbs_users.lasttname, ' ', nbs_users.firstname, ' ') as fio FROM po_main_table 
        INNER JOIN nbs_users ON nbs_users.id =  po_main_table.answering_id 
        INNER JOIN fr_scripts ON fr_scripts.script_id =  po_main_table.fr_script_id 
        INNER JOIN fr_status_values ON fr_status_values.fr_status_id =  po_main_table.fr_status 
        INNER JOIN nbs_orders_status_action ON nbs_orders_status_action.proj_id =  po_main_table.proj_id 
        WHERE po_main_table.fr_date >=:frDateOne 
        AND DATE(nbs_orders_status_action.created_at) =:date 
        GROUP BY nbs_users.id
        ";
        $params = [
            'frDateOne' => $frDateOne,
            'date' => DATE('Y-m-d')
        ];
        return Db::getInstance()->queryAll($sql, $params);
    }


    public function getNotStatus()
    {
        $sql = " SELECT  proj_id, internal_id from po_main_table  
        WHERE po_main_table.fr_date >='2020-01-01'
        AND fr_status = 0";
        return Db::getInstance()->queryAll($sql);
    }

    public function getForQuotesTable()
    {
        $sql = "SELECT
                fmt.fr_date, fmt.internal_id, fmt.proj_id, fmt.status_client,
                fs.company_name,
                nod.disease_id,
                nd.disease_name, nd.disease_name_russian_old,
                ndbt.biospecimen_type_id,
                nbt.biospecimen_type,
                fmt.history_quotes_search,
                fmt.clinical_inclusion
            FROM po_main_table fmt
                JOIN fr_scripts fs ON fmt.fr_script_id = fs.script_id
                RIGHT OUTER JOIN nbs_orders_diseases nod ON fmt.proj_id = nod.order_id
                JOIN nbs_disease nd ON nod.disease_id = nd.id 
                RIGHT OUTER JOIN nbs_diseases_biospecimen_types ndbt 
                    ON (fmt.proj_id = ndbt.order_id and nod.disease_id = ndbt.disease_id) 
                JOIN nbs_biospecimen_type nbt ON ndbt.biospecimen_type_id = nbt.id
            WHERE
                fmt.status_client IN (27, 33)
                OR fmt.history_quotes_search = 1";

        return Db::getInstance()->queryAll($sql);
    }


    public function getCommunicationNow($day)
    {

        $sql = "SELECT
                *
            FROM po_main_table fmt
            WHERE
                fmt.status_client IN (27)
                AND fmt.communication_date <= :date
              ORDER by fmt.communication_date ASC
                ";
        $params = [
            'date' => $day
        ];
        return Db::getInstance()->queryAll($sql, $params);
    }

    public function GetCommunicationStatusLog($day)
    {

        $sql = "SELECT
                nosa.proj_id, nosa.created_at
            FROM po_main_table fmt
            INNER JOIN nbs_orders_status_action nosa ON nosa.proj_id = fmt.proj_id
            WHERE
                fmt.status_client IN (27)
                AND nosa.orders_status_id IN (27)
                -- AND DATE(nosa.created_at) > '2021-06-01'
                AND fmt.communication_date <= :date
              ORDER by nosa.created_at DESC
                ";
        $params = [
            'date' => $day
        ];
        return Db::getInstance()->queryAll($sql, $params);
    }


    public function GetCommunicationManager($day)
    {

        $sql = "SELECT
                *
            FROM po_main_table fmt
            INNER JOIN nbs_orders_status_action nosa ON nosa.proj_id = fmt.proj_id
            WHERE
                fmt.status_client IN (27)
                AND nosa.orders_status_id IN (27)
                AND fmt.communication_date <= :date
              ORDER by nosa.created_at DESC
                ";
        $params = [
            'date' => $day
        ];
        return Db::getInstance()->queryAll($sql, $params);
    }


    public function GetCommunicationStaf($day)
    {

        $sql = "SELECT
                nosa.proj_id, nss.name
            FROM po_main_table fmt
            INNER JOIN nbs_scripts_staff nss ON nss.id = fmt.attention_to
            INNER JOIN nbs_orders_status_action nosa ON nosa.proj_id = fmt.proj_id
            WHERE
                fmt.status_client IN (27)
                AND nosa.orders_status_id IN (27)
                AND fmt.communication_date <= :date
              ORDER by nosa.created_at DESC
                ";
        $params = [
            'date' => $day
        ];
        return Db::getInstance()->queryAll($sql, $params);
    }


    public function GetCommunicationGenerate()
    {

        $sql = "SELECT
                nosa.proj_id, nosa.created_at
            FROM po_main_table fmt
            INNER JOIN nbs_orders_status_action nosa ON nosa.proj_id = fmt.proj_id
            WHERE
                fmt.status_client IN (27)
                AND nosa.orders_status_id IN (27)
                AND DATE(nosa.created_at) > '2021-06-01'
               -- AND fmt.communication_date <= :date
              ORDER by nosa.created_at DESC
                ";
        $params = [
            'date' => $day
        ];
        return Db::getInstance()->queryAll($sql, $params);
    }


    public function getOrdersStaff()
    {

        $sql = "SELECT
            fmt.proj_id, nss.name
            FROM po_main_table fmt
            INNER JOIN nbs_scripts_staff nss ON nss.id = fmt.attention_to
             ORDER by fmt.proj_id DESC
                ";

        return Db::getInstance()->queryAll($sql);
    }




    public function getAllLastYear()
    {

        $sql = "SELECT * 
            FROM po_main_table fmt
            WHERE fmt.fr_date >= '2021-01-01'
            ORDER by fmt.proj_id ASC
                ";

        return Db::getInstance()->queryAll($sql);
    }


    public function getAllLastReportClient()
    {

        $sql = "SELECT * 
            FROM po_main_table fmt
            INNER JOIN fr_scripts ON fr_scripts.script_id = fmt.fr_script_id
            WHERE fmt.fr_date >= '2021-01-01'
            AND fmt.status_client != 33
            AND fmt.status_client != 29
            AND fmt.status_client != 28
            ORDER by fmt.fr_date ASC
                ";

        return Db::getInstance()->queryAll($sql);
    }

    public function getAllLastReportClientbyDate($dateOne, $dateTwo)
    {

        $sql = "SELECT * 
            FROM po_main_table fmt
            INNER JOIN fr_scripts ON fr_scripts.script_id = fmt.fr_script_id
            WHERE fmt.fr_date >= :dateOne
              AND fmt.fr_date <= :dateTwo
            AND fmt.status_client != 33
            AND fmt.status_client != 29
            AND fmt.status_client != 28
            ORDER by fmt.fr_date ASC
                ";

        $params = [
            'dateOne' => $dateOne,
            'dateTwo' => $dateTwo
        ];




        return Db::getInstance()->queryAll($sql, $params);
    }

}
