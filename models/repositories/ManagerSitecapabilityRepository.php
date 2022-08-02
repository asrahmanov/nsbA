<?php


namespace app\models\repositories;

use app\engine\App;
use app\models\entities\ManagerSitecapability;
use app\models\Repository;
use app\engine\Db;

class ManagerSitecapabilityRepository extends Repository
{

    public function getTableName()
    {
        return 'nbs_manager_sitecapability';
    }

    public function getIdName()
    {
        return 'id';
    }
    public function getEntityClass()
    {
        return ManagerSitecapability::class;
    }


    public function getByUser($user_id)
    {
        $sql = "SELECT *, nbs_template_sitecapability.name as template_name, nbs_manager_sitecapability.id as id, nbs_manager_sitecapability.created_at as created_at FROM nbs_manager_sitecapability 
        INNER JOIN fr_sites ON fr_sites.site_id = nbs_manager_sitecapability.site_id
        INNER JOIN nbs_template_sitecapability ON nbs_template_sitecapability.id = nbs_manager_sitecapability.template_id 
        WHERE nbs_manager_sitecapability.user_id=:user_id ";
        $params = ['user_id' => $user_id];
        return Db::getInstance()->queryAll($sql, $params);

    }

    public function getByUserAndTemplate($id)
    {

        $sql = "SELECT *, nbs_template_sitecapability.name as template_name, nbs_manager_sitecapability.id as id  FROM nbs_manager_sitecapability 
        INNER JOIN fr_sites ON fr_sites.site_id = nbs_manager_sitecapability.site_id
        INNER JOIN nbs_template_sitecapability ON nbs_template_sitecapability.id = nbs_manager_sitecapability.template_id 
        WHERE nbs_manager_sitecapability.id=:id 
        AND nbs_manager_sitecapability.user_id=:user_id
        ";

        $user_id = App::call()->session->getSession('user_id');
        $params = [
            'id' => $id,
            'user_id' => $user_id
            ];
        return Db::getInstance()->queryOne($sql, $params);

    }



    public function getAllByStatus($status_id)
    {

        $sql = "SELECT 
        *, nbs_template_sitecapability.name as template_name, 
        nbs_manager_sitecapability.id as id, 
        nbs_diseases_sitecapability.id as diseasesid, 
        nbs_tissues_sitecapability.id as tissuesid
        FROM nbs_manager_sitecapability 
        INNER JOIN fr_sites ON fr_sites.site_id = nbs_manager_sitecapability.site_id 
        INNER JOIN nbs_template_sitecapability ON nbs_template_sitecapability.id = nbs_manager_sitecapability.template_id 
        INNER JOIN nbs_manager_site_capability_answer ON nbs_manager_sitecapability.id = nbs_manager_site_capability_answer.manager_site_capability_id 
        INNER JOIN nbs_users ON nbs_users.id = nbs_manager_sitecapability.user_id 
        INNER JOIN nbs_diseases_sitecapability ON nbs_diseases_sitecapability.id = nbs_manager_site_capability_answer.diseases_sitecapability_id 
        INNER JOIN nbs_tissues_sitecapability ON nbs_tissues_sitecapability.id = nbs_manager_site_capability_answer.tissues_sitecapability_id 
        WHERE nbs_manager_sitecapability.status_id=:status_id 
        ";
        $user_id = App::call()->session->getSession('user_id');
        $params = [
            'status_id' => $status_id,
        ];

        return Db::getInstance()->queryAll($sql, $params);

    }

    public function getAllByStatusAndtemplate($status_id, $template_id, $diseases_val, $tissues_val)
    {

        $sql = "SELECT 
        *, nbs_template_sitecapability.name as template_name, 
        nbs_manager_sitecapability.id as id, 
        nbs_diseases_sitecapability.id as diseasesid, 
        nbs_tissues_sitecapability.id as tissuesid,
        nbs_manager_site_capability_answer.created_at as dates
        FROM nbs_manager_sitecapability 
        INNER JOIN fr_sites ON fr_sites.site_id = nbs_manager_sitecapability.site_id 
        INNER JOIN nbs_template_sitecapability ON nbs_template_sitecapability.id = nbs_manager_sitecapability.template_id 
        INNER JOIN nbs_manager_site_capability_answer ON nbs_manager_sitecapability.id = nbs_manager_site_capability_answer.manager_site_capability_id 
        INNER JOIN nbs_users ON nbs_users.id = nbs_manager_sitecapability.user_id 
        INNER JOIN nbs_diseases_sitecapability ON nbs_diseases_sitecapability.id = nbs_manager_site_capability_answer.diseases_sitecapability_id 
        INNER JOIN nbs_tissues_sitecapability ON nbs_tissues_sitecapability.id = nbs_manager_site_capability_answer.tissues_sitecapability_id 
        WHERE nbs_manager_sitecapability.template_id=:template_id
        AND nbs_manager_sitecapability.status_id=:status_id
        AND nbs_tissues_sitecapability.id =:tissues_val
        AND nbs_diseases_sitecapability.id=:diseases_val
        ORDER by nbs_manager_site_capability_answer.id DESC
        ";
        $user_id = App::call()->session->getSession('user_id');
        $params = [
            'status_id' => $status_id,
            'template_id'=> $template_id,
            'tissues_val' => $tissues_val,
            'diseases_val' => $diseases_val
        ];

        return Db::getInstance()->queryAll($sql, $params);

    }

    public function getForReport ()
    {
        $sql = "
            SELECT 
                   CONCAT(nbs_u.firstname, ' ', nbs_u.lasttname) as fio, nbs_slws.work_status, nbs_msca.answer,
                   nbs_ms.template_id, nbs_msca.diseases_sitecapability_id as disease_id,
                   nbs_msca.tissues_sitecapability_id as tissue, fr_sites.site_id, fr_sites.site_name,
                   nbs_u.id as user_id, nbs_ds.disease, nbs_td.name as template
            FROM nbs_manager_sitecapability nbs_ms
                JOIN fr_sites ON fr_sites.site_id = nbs_ms.site_id 
                JOIN nbs_users nbs_u ON nbs_u.id = nbs_ms.user_id
                left outer JOIN (
                    SELECT MAX(nbs_site_log_work_status.id) max_id, site_id 
                    FROM nbs_site_log_work_status
                    GROUP BY site_id
                ) last_status ON (last_status.site_id = fr_sites.site_id)
                left outer JOIN nbs_site_log_work_status nbs_slws ON nbs_slws.id = last_status.max_id
                left outer JOIN nbs_manager_site_capability_answer nbs_msca ON nbs_ms.id = nbs_msca.manager_site_capability_id
                left outer JOIN nbs_diseases_sitecapability nbs_ds ON nbs_msca.diseases_sitecapability_id = nbs_ds.id
                left outer JOIN nbs_template_sitecapability nbs_td ON nbs_ds.template_id = nbs_td.id
        ";
        $params = [];
        return Db::getInstance()->queryAll($sql, $params);
    }

}
