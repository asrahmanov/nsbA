<?php


namespace app\models;
use app\engine\Db;

abstract class Repository
{


    public function getOne($id)
    {
        $idName = $this->getIdName();
        $tableName = $this->getTableName();
        $sql = "SELECT * FROM {$tableName} WHERE $idName = :$idName";
        return Db::getInstance()->queryOne($sql, [$idName => $id]);
    }


    public function getObject($id)
    {
        $tableName = $this->getTableName();
        $idName = $this->getIdName();

        $sql = "SELECT * FROM {$tableName} WHERE $idName = :$idName";
        return Db::getInstance()->queryObject($sql, [$idName => $id], $this->getEntityClass());
    }


    public function getAll()
    {
        $tableName = $this->getTableName();
        $sql = "SELECT * FROM {$tableName}";
        return Db::getInstance()->queryAll($sql);
    }

    public function getAllnotDeleted()
    {
        $tableName = $this->getTableName();
        $sql = "SELECT * FROM {$tableName} WHERE deleted != 1";
        return Db::getInstance()->queryAll($sql);
    }


    public function getAllDeleted()
    {
        $tableName = $this->getTableName();
        $sql = "SELECT * FROM {$tableName} WHERE deleted != 1";
        return Db::getInstance()->queryAll($sql);
    }

    public function getAllDatatable()
    {
        $tableName = $this->getTableName();
        $sql = "SELECT * FROM {$tableName}";
        $array = Db::getInstance()->queryAll($sql);
        $json['data'] = [];
        for ($i = 0; $i < count($array); $i++) {
            foreach ($array[$i] as $key => $value) {
                $json['data'][$i][$key] = $value;
            }
        }
        return $json;
    }

    public function insert($entity)
    {

        $idName = $this->getIdName();
        $columsNames = '';
        $values = '';
        $params = [];
        $tableName = $this->getTableName();

        foreach ($entity->arrayParams as $key => $value) {
            if ($entity->$key == '') {
                $entity->$key = NULL;
            };

            $columsNames .= $key . ',';
            $values .= ":{$key},";
            $params[$key] = $entity->$key;
            //var_dump($params[$key]);
        }
        $columsNames = substr($columsNames, 0, -1);
        $values = substr($values, 0, -1);
        $sql = "INSERT INTO {$tableName}  ({$columsNames}) VALUES  ($values)";
        Db::getInstance()->execute($sql, $params);
//        var_dump($sql);
//        var_dump($params);
        return $this->$idName = Db::getInstance()->lastInsertId();
    }


    public function save(Model $entity)
    {
        $idName = $this->getIdName();

        if (is_null($entity->$idName)) {
            return $this->insert($entity);
        } else {
            return $this->update($entity);
        }
    }

    public function update(Model $entity)
    {
        if (count($entity->arrayParams) > 0) {
            $idName = $this->getIdName();
            $columsNames = '';
            $params = [];
            $params["{$idName}"] = $entity->{$idName};
            $tableName = $this->getTableName();

            foreach ($entity->arrayParams as $key => $value) {
                if ($entity->arrayParams[$key] == true) {
                    if ($entity->$key == '') {
                        $entity->$key = NULL;
                    };
                    $columsNames .= $key . "=:{$key}, ";
                    $params[$key] = $entity->$key;
                }
            }
            $columsNames = substr($columsNames, 0, -2);
            $sql = "UPDATE  {$tableName} SET  {$columsNames} WHERE $idName=:$idName";
            $result = Db::getInstance()->execute($sql, $params);
//            var_dump($sql);
//            var_dump($params);
            return $result;

        }

    }


    public function delete(Model $entity)
    {
        $idName = $this->getIdName();
        $tableName = $this->getTableName();
        $sql = "DELETE FROM {$tableName} WHERE $idName=:$idName";
        return Db::getInstance()->queryOne($sql, [$idName => $entity->$idName]);
    }

    public function getWhere($columsAndParams)
    {
        $tableName = $this->getTableName();
        $columsNames = '';
        $params = [];
        foreach ($columsAndParams as $key => $value) {
            $columsNames .= $key . "=:{$key} AND ";
            $params[$key] = $value;
        }
        $columsNames = substr($columsNames, 0, -5);
        $sql = "SELECT * FROM {$tableName} WHERE $columsNames";
        return Db::getInstance()->queryAll($sql, $params);
    }


    public function deleteWhere($columsAndParams, $limit = '')
    {

        $tableName = $this->getTableName();
        $columsNames = '';
        $params = [];

        foreach ($columsAndParams as $key => $value) {
            $columsNames .= $key . "=:{$key} AND ";
            $params[$key] = $value;
        }
        $columsNames = substr($columsNames, 0, -5);

        if ($limit != '') {
            $limit_sql = "LIMIT " . $limit;
        }

        $sql = "DELETE FROM {$tableName} WHERE $columsNames $limit_sql";
        return Db::getInstance()->execute($sql, $params);

    }


    public function getCountWhere($columsAndParams)
    {
        $tableName = $this->getTableName();
        $columsNames = '';
        $params = [];

        foreach ($columsAndParams as $key => $value) {
            $columsNames .= $key . "=:{$key} AND ";
            $params[$key] = $value;
        }
        $columsNames = substr($columsNames, 0, -5);

        $sql = "SELECT count(*) as count FROM {$tableName} WHERE $columsNames";
        return Db::getInstance()->queryOne($sql, $params)['count'];

    }


}
