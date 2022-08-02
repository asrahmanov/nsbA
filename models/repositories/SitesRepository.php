<?php


namespace app\models\repositories;

use app\engine\App;
use app\models\entities\Sites;
use app\models\Repository;
use app\engine\Db;


class SitesRepository extends Repository
{

    public function getTableName()
    {
        return 'fr_sites';
    }

    public function getEntityClass()
    {
        return Sites::class;
    }

    public function getIdName()
    {
        return 'site_id';
    }



    public function GetSitesOne($site_id)
    {
        $tableName = $this->getTableName();
        $idName = $this->getIdName();
        $sql = "SELECT * FROM {$tableName} WHERE  $idName=:$idName ";
        $params = [$idName => $site_id];
        $result = Db::getInstance()->queryOne($sql,$params);
        return $result;
    }

    public function getSeitesAll(){
        $sql = "SELECT *, fr_site_types.site_type as type_name FROM fr_sites
        INNER JOIN fr_site_types ON fr_site_types.site_type_id = fr_sites.site_type
        INNER JOIN fr_cities ON fr_cities.city_id = fr_sites.city
        ";
        $result =  Db::getInstance()->queryAll($sql);
        return $result;
    }

    public function getOptions () {
        $sql = "
            SELECT site_id, site_name, site_code
            FROM fr_sites
            WHERE site_name NOT IN ('', '-')
            ORDER BY site_id";
        $result = Db::getInstance()->queryAll($sql);
        return $result;
    }






}
