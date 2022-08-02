<?php


namespace app\models\repositories;

use app\engine\App;
use app\models\entities\Files;
use app\models\Repository;
use app\engine\Db;


class FilesRepository extends Repository
{

    public function getTableName()
    {
        return 'nbs_files';
    }

    public function getEntityClass()
    {
        return Files::class;
    }

    public function getIdName()
    {
        return 'id';
    }

    public function getFilesbyProjId($proj_id)
    {
        $tableName = $this->getTableName();
        // App::call()->session->checkSessionAndDestroy();
        $params = ['proj_id' => $proj_id];
        $sql = "SELECT * FROM {$tableName} WHERE proj_id=:proj_id 
        AND deleted = '0'";
        return Db::getInstance()->queryAll($sql, $params);
    }

    public function uploadFiles ($pojectFolder, $info) {

        $dirname = 'upload/' . $pojectFolder . '/';
        $file_tmp = $_FILES['file']['tmp_name'];
        $file_name = $_FILES['file']['name'];
        $file_destination = $dirname . $file_name;

        // Проверяем существует ли директория
        if (file_exists($dirname)) {
            //echo "Директория существует";
        } else {
            mkdir($dirname);
        }
        // Проверям судещствуе ли такой файл
        if (file_exists($file_destination)) {

            return "Данный файл уже существует";

        } else {

            if (move_uploaded_file($file_tmp, $file_destination)) {
                $file = new Files();
                $file->name = $_FILES['file']['name'];
                $file->alias = $dirname;
                $file->proj_id = $pojectFolder;
                $file->info = $info;
                return $this->save($file);
//                return "Файл успешно загружен";

            }

        }
    }






}
