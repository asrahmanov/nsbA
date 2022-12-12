<?php


namespace app\models\repositories;

use app\engine\App;
use app\models\entities\Users;
use app\engine\Db;
use app\models\Repository;


class UsersRepository extends Repository
{

    public static function getTableName()
    {
        return 'nbs_users';
    }

    public function getIdName()
    {
        return 'id';
    }


    public function getEntityClass()
    {
        return Users::class;
    }


    public function isAuth()
    {
        return App::call()->session->checkSession('login');
    }


    public function auth($login, $password)
    {
        $user = $this->getWhere([
            'login' => $login,
            'deleted' => 0
        ]);
        if (count($user) > 0) {
            $result = password_verify($password, $user[0]['password']);
            if ($result) {
                App::call()->session->setSession('login', $user[0]['login']);
                App::call()->session->setSession('firstname', $user[0]['firstname']);
                App::call()->session->setSession('lasttname', $user[0]['lasttname']);
                App::call()->session->setSession('patronymic', $user[0]['patronymic']);
                App::call()->session->setSession('role_id', $user[0]['role_id']);
                App::call()->session->setSession('user_id', $user[0]['id']);
                App::call()->session->setSession('department_id', $user[0]['department_id']);
                return $user[0];
            }
        } else {
            return false;
        }
        return $result;
    }


    public function authAdmin($login, $password)
    {
        $user = $this->getWhere(['login' => $login]);
        if (count($user) > 0) {
            if ($password == $user[0]['password']) {
                $result = true;
            }
            if ($result) {
                App::call()->session->setSession('login', $user[0]['login']);
                App::call()->session->setSession('firstname', $user[0]['firstname']);
                App::call()->session->setSession('lasttname', $user[0]['lasttname']);
                App::call()->session->setSession('patronymic', $user[0]['patronymic']);
                App::call()->session->setSession('role_id', $user[0]['role_id']);
                App::call()->session->setSession('user_id', $user[0]['id']);
                App::call()->session->setSession('department_id', $user[0]['department_id']);
                return $user[0];
            }
        } else {
            return false;
        }
        return $result;
    }


    public function checkUserInRegister($login)
    {
        $result = $this->getWhere(['login' => $login]);
        if (count($result) > 0) {
            return true;
        }
        return false;
    }


    public function getThemeUsers($user_id)
    {
        $tableName = $this->getTableName();
        $idName = $this->getIdName();
        // App::call()->session->checkSessionAndDestroy();
        $sql = "SELECT color,img_bg FROM {$tableName} WHERE  $idName=:$idName ";
        $params = [$idName => $user_id];
        $result = Db::getInstance()->queryOne($sql, $params);

        return $result;
    }


    public function randomPassword()
    {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array();
        $alphaLength = strlen($alphabet) - 1;
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass);
    }


    public function getWhereOrder($columsAndParams)
    {
        $tableName = $this->getTableName();
        $columsNames = '';
        $params = [];
        foreach ($columsAndParams as $key => $value) {
            $columsNames .= $key . "=:{$key} AND ";
            $params[$key] = $value;
        }
        $columsNames = substr($columsNames, 0, -5);
        $sql = "SELECT * FROM {$tableName} WHERE $columsNames ORDER by lasttname ASC ";
        return Db::getInstance()->queryAll($sql, $params);
    }


    public function getWhereOrderOnlyManager()
    {
        $tableName = $this->getTableName();
        $sql = "SELECT * FROM {$tableName} 
        WHERE role_id=:role_one
        OR role_id=:role_two_
        ORDER by lasttname ASC
        ";
        $params = [
            'role_one' => 3,
            'role_two_' => 8,
        ];

        return Db::getInstance()->queryAll($sql, $params);
    }


    public function getEmail($user_id)
    {
        $tableName = $this->getTableName();
        $idName = $this->getIdName();
        // App::call()->session->checkSessionAndDestroy();
        $sql = "SELECT email FROM {$tableName} WHERE  $idName=:$idName ";
        $params = [$idName => $user_id];
        $result = Db::getInstance()->queryOne($sql, $params);
        return $result;
    }

    // Переопределение функции get all
    public function getAll()
    {
        $role_id = 6;
        $user_id = App::call()->session->getSession('user_id');
        $tableName = $this->getTableName();
        $sql = "SELECT * FROM {$tableName} 
        WHERE role_id!=:role_id
        ";
        $params = [
            'role_id' => $role_id
        ];
        return Db::getInstance()->queryAll($sql, $params);
    }

    public function getEverybody()
    {
        $tableName = $this->getTableName();
        $sql = "SELECT * FROM {$tableName}";
        return Db::getInstance()->queryAll($sql);
    }


    public function getUserJoinSites($role_id)
    {
        $sql = "SELECT * FROM nbs_manager_sites
        INNER JOIN nbs_users ON nbs_manager_sites.user_id = nbs_users.id
        INNER JOIN fr_sites ON nbs_manager_sites.site_id = fr_sites.site_id
        WHERE nbs_users.role_id=:role_id
        ";
        $params = [
            'role_id' => $role_id
        ];
        return Db::getInstance()->queryAll($sql, $params);
    }

    public function getUserJoinSitesByDepart()
    {
        $sql = "SELECT * FROM nbs_manager_sites
        INNER JOIN nbs_users ON nbs_manager_sites.user_id = nbs_users.id
        INNER JOIN fr_sites ON nbs_manager_sites.site_id = fr_sites.site_id
        WHERE nbs_users.id=:user_1 
        OR nbs_users.id=:user_2 
              
        ";
        $params = [
            'user_1' => '23',
            'user_2' => '25'
        ];
        return Db::getInstance()->queryAll($sql, $params);
    }


    public function getUserJoinSitesByUserId($user_id)
    {
        $sql = "SELECT * FROM nbs_manager_sites
        INNER JOIN nbs_users ON nbs_manager_sites.user_id = nbs_users.id
        INNER JOIN fr_sites ON nbs_manager_sites.site_id = fr_sites.site_id
        WHERE nbs_users.id =:user_id
        ";
        $params = [
            'user_id' => $user_id
        ];
        return Db::getInstance()->queryAll($sql, $params);
    }


    public function getUserALLJoinSitesALL()
    {
        $sql = "SELECT * FROM nbs_manager_sites
        INNER JOIN nbs_users ON nbs_manager_sites.user_id = nbs_users.id
        INNER JOIN fr_sites ON nbs_manager_sites.site_id = fr_sites.site_id
        ";
        return Db::getInstance()->queryAll($sql);
    }

    public function getManager()
    {

        $tableName = $this->getTableName();
        $sql = "SELECT * FROM {$tableName} 
        WHERE (role_id=:role_one
        OR role_id=:role_two)
        AND deleted != 1
        ";
        $params = [
            'role_one' => 3,
            'role_two' => 8

        ];
        return Db::getInstance()->queryAll($sql, $params);
    }

    public function getManagers()
    {

        $tableName = $this->getTableName();
        $sql = "SELECT nbs_users.id as user_id ,nbs_users.firstname, nbs_users.lasttname, nbs_users.patronymic, nbs_users.phone, nbs_users.email, nbs_users.en_position, nbs_users.ru_position, nbs_role.name as role  FROM nbs_users
        INNER JOIN nbs_role ON nbs_users.role_id = nbs_role.id
        WHERE (nbs_users.role_id=:role_one
        OR nbs_users.role_id=:role_two)
        AND nbs_users.deleted != 1
        ";
        $params = [
            'role_one' => 3,
            'role_two' => 8

        ];
        return Db::getInstance()->queryAll($sql, $params);
    }




    public function getManagersNotBiobank()
    {

        $tableName = $this->getTableName();
        $sql = "SELECT nbs_users.id as user_id ,nbs_users.firstname, nbs_users.lasttname, nbs_users.patronymic, nbs_users.phone, nbs_users.email, nbs_users.en_position, nbs_users.ru_position, nbs_role.name as role  FROM nbs_users
        INNER JOIN nbs_role ON nbs_users.role_id = nbs_role.id
        WHERE (nbs_users.role_id=:role_one
        OR nbs_users.role_id=:role_two)
        AND nbs_users.deleted != 1
        AND nbs_users.id != 91
        AND nbs_users.id != 95
        AND nbs_users.id != 96
        ";
        $params = [
            'role_one' => 3,
            'role_two' => 8

        ];
        return Db::getInstance()->queryAll($sql, $params);
    }


    public function getManagerNotPrimat()
    {

        $tableName = $this->getTableName();
        $sql = "SELECT * FROM nbs_users
        WHERE (role_id=:role_one
        OR role_id=:role_two)
        AND deleted != 1
        AND department_id!=:department_id
        AND nbs_users.id != 91
        AND nbs_users.id != 95
        AND nbs_users.id != 96
        ";
        $params = [
            'role_one' => 3,
            'role_two' => 8,
            'department_id' => 10

        ];
        return Db::getInstance()->queryAll($sql, $params);
    }

    public function getManagerBiobank()
    {

        $tableName = $this->getTableName();
        $sql = "SELECT * FROM nbs_users
        WHERE 
        deleted != 1
        AND nbs_users.id in(91,95,96)
        ";

        return Db::getInstance()->queryAll($sql);
    }


    public function getManagerPrimat()
    {
        $tableName = $this->getTableName();
        $sql = "SELECT * FROM {$tableName} 
        WHERE (role_id=:role_one
        OR role_id=:role_two)
        AND deleted != 1
        AND department_id=:department_id
        AND nbs_users.id != 91
        AND nbs_users.id != 95
        AND nbs_users.id != 96
        ";
        $params = [
            'role_one' => 3,
            'role_two' => 8,
            'department_id' => 10

        ];
        return Db::getInstance()->queryAll($sql, $params);
    }

    public function getUsersByRole($role)
    {

        $tableName = $this->getTableName();
        $sql = "SELECT * FROM {$tableName} 
        WHERE role_id=:role
        AND {$tableName}.deleted != 1
        
        ";
        $params = [
            'role' => $role,
        ];
        return Db::getInstance()->queryAll($sql, $params);
    }


    public function getUsersByDepartemant($department_id)
    {

        $tableName = $this->getTableName();
        $sql = "SELECT * FROM {$tableName} 
        WHERE department_id=:department_id
        AND {$tableName}.deleted != 1
        ";
        $params = [
            'department_id' => $department_id,
        ];
        return Db::getInstance()->queryAll($sql, $params);

    }


    public function getUsersById($user_id)
    {

        $tableName = $this->getTableName();
        $sql = "SELECT * FROM {$tableName} 
        WHERE id=:user_id
        LIMIT 1
        ";
        $params = [
            'user_id' => $user_id,
        ];
        return Db::getInstance()->queryAll($sql, $params);

    }


    public function getUsersAdminNotDelete($role_id)
    {

        $tableName = $this->getTableName();
        $sql = "SELECT * FROM {$tableName} 
        WHERE role_id=:role_id
        AND deleted != 1
        ";
        $params = [
            'role_id' => $role_id,
        ];
        return Db::getInstance()->queryAll($sql, $params);

    }

}
