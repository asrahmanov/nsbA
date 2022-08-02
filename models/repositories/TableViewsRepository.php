<?php


namespace app\models\repositories;

use app\engine\App;
use app\models\entities\TableViews;
use app\models\Repository;
use app\engine\Db;


class TableViewsRepository extends Repository
{

    public function getTableName()
    {
        return 'nbs_table_view';
    }

    public function getEntityClass()
    {
        return TableViews::class;
    }


    public function getIdName()
    {
        return 'id';
    }


    public function getTableView($table_name)
    {

        $tableName = $this->getTableName();
        $user_id = App::call()->session->getSession('user_id');
        $sql = "SELECT * FROM {$tableName} WHERE table_name=:table_name AND user_id=:user_id";
        $params = [
            'user_id' => $user_id,
            'table_name' => $table_name
        ];
        $result = Db::getInstance()->queryAll($sql, $params);
        return $result;
    }



    public function getViewOne($table_name, $column_name) {

        $tableName = $this->getTableName();
        $user_id = App::call()->session->getSession('user_id');
        $sql = "SELECT * FROM {$tableName} WHERE  table_name=:table_name AND column_name=:column_name AND user_id=:user_id";
        $params = [
            'user_id' => $user_id,
            'table_name' => $table_name,
            'column_name' => $column_name
        ];
        $result = Db::getInstance()->queryAll($sql, $params);
        return $result;

    }


}
