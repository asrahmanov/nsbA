<?php


namespace app\models\repositories;

use app\engine\Db;
use app\models\entities\ClinicalCase;
use app\models\Repository;

class ClinicalCaseRepository extends Repository
{

    public function getTableName()
    {
        return 'nbs_clinical_case';
    }

    public function getIdName()
    {
        return 'id';
    }

    public function getEntityClass()
    {
        return ClinicalCase::class;
    }


    public function getAllbyDateDesc($dateOne, $dateTwo)
    {

        $sql = "SELECT * FROM nbs_clinical_case 
        WHERE (nbs_clinical_case.clinical_date >=:dateOne 
        AND nbs_clinical_case.clinical_date <=:dateTwo)
        ORDER by nbs_clinical_case.clinical_date DESC
        ";

        $params = [
            'dateOne' => $dateOne,
            'dateTwo' => $dateTwo
        ];

        return Db::getInstance()->queryAll($sql, $params);

    }

    public function getAllbyDateDescNotCheck($dateOne, $dateTwo)
    {

        $sql = "SELECT * FROM nbs_clinical_case 
        WHERE (nbs_clinical_case.clinical_date >=:dateOne 
        AND nbs_clinical_case.clinical_date <=:dateTwo)
        AND (checked != 1
        OR paid != 1
        OR form_valid != 1)
        AND not_on_mailing_list != 1
        ORDER by nbs_clinical_case.clinical_date DESC
        ";

        $params = [
            'dateOne' => $dateOne,
            'dateTwo' => $dateTwo
        ];

        return Db::getInstance()->queryAll($sql, $params);

    }


    public function isNullfix()
    {

        $sql = "
                update nbs_clinical_case set not_on_mailing_list = 0 
                where not_on_mailing_list IS NULL;
        ";

        return Db::getInstance()->queryAll($sql);

    }

//    public function getManagerByDateDesc ($dateOne, $dateTwo, $user_id)
//    {
//
//        $sql = "
//        SELECT * FROM nbs_clinical_case
//        WHERE (nbs_clinical_case.clinical_date >=:dateOne
//        AND nbs_clinical_case.clinical_date <=:dateTwo)
//        ORDER by nbs_clinical_case.clinical_date DESC
//        ";
//
//        $params = [
//            'dateOne' => $dateOne,
//            'dateTwo' => $dateTwo
//        ];
//
//        return Db::getInstance()->queryAll($sql, $params);
//
//    }

    public function getLost()
    {

        $dateOne = DATE('Y-m-d');
        $dateAt = strtotime('-5 DAYS', strtotime($dateOne));
        $dateTwo = date('Y-m-d', $dateAt);
        $sql = "SELECT * FROM nbs_clinical_case 
        WHERE (nbs_clinical_case.clinical_date >='2020-11-01' 
        AND nbs_clinical_case.clinical_date <=:dateTwo)
        AND checked != 1 
        AND not_on_mailing_list != 1 
        ORDER by nbs_clinical_case.clinical_date DESC
        ";

        $params = [
            'dateTwo' => $dateTwo
        ];

        return Db::getInstance()->queryAll($sql, $params);

    }


}
