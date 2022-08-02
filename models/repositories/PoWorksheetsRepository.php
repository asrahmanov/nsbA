<?php

namespace app\models\repositories;

use app\engine\App;
use app\models\entities\Mail;
use app\models\entities\Poworksheets;


use app\models\Repository;
use app\engine\Db;

class PoWorksheetsRepository extends Repository
{

    public function getEntityClass()
    {
        return Poworksheets::class;
    }

    public function getIdName()
    {
        return 'id';
    }

    public function getTableName()
    {
        return 'po_worksheets';
    }

    // TODO po_work

    public function getJoinOrders()
    {
        $user_id = App::call()->session->getSession('user_id');
        $sql = "SELECT 
        po_main_table.proj_id,
        po_main_table.fr_date, 
        po_main_table.internal_id, 
        po_main_table.external_id, 
        po_main_table.project_name, 
        po_main_table.project_details, 
        po_main_table.feas_russian, 
        po_main_table.comments, 
        po_main_table.deadline,
        nbs_worksheets_status.name as worksheets_status,
        po_worksheets.id as worksheets_id
        FROM po_main_table 
        INNER JOIN po_worksheets ON po_main_table.proj_id = po_worksheets.proj_id
        INNER JOIN nbs_worksheets_status ON nbs_worksheets_status.id = po_worksheets.status_id
        WHERE  po_worksheets.user_id=:user_id
        ORDER by po_main_table.fr_date  DESC";
        $params = ['user_id' => $user_id];
        return Db::getInstance()->queryAll($sql, $params);
    }

    public function getJoinOrdersByWorksheetsId($id)
    {

        $user_id = App::call()->session->getSession('user_id');
        $sql = "SELECT 
        fr_main_table.proj_id,
        fr_main_table.fr_date, 
        fr_main_table.internal_id, 
        fr_main_table.external_id, 
        fr_main_table.project_name, 
        fr_main_table.project_details, 
        fr_main_table.feas_russian, 
        fr_main_table.comments, 
        fr_main_table.deadline,
        fr_main_table.answering_id,
        nbs_worksheets_status.name as worksheets_status,
        po_worksheets.id as worksheets_id
        FROM fr_main_table 
        INNER JOIN po_worksheets ON fr_main_table.proj_id = po_worksheets.proj_id
        INNER JOIN nbs_worksheets_status ON nbs_worksheets_status.id = po_worksheets.status_id
        WHERE  po_worksheets.id=:id
        AND po_worksheets.user_id=:user_id
        ";
        $params = [
            'id' => $id,
            'user_id' => $user_id
        ];
        return Db::getInstance()->queryAll($sql, $params);
    }

    public function generateOnebyOneOrders($proj_id)
    {

        $nbs_users = App::call()->usersRepository->getManagerNotPrimat();


        for ($j = 0, $jMax = count($nbs_users); $j < $jMax; $j++) {
            $result = 0;
            $worksheets = new Worksheets();
            $worksheets->proj_id = $proj_id;
            $worksheets->user_id = $nbs_users[$j]['id'];
            $worksheets->status_id = 1;
            $resultW = $this->save($worksheets);

            if ($resultW > 0) {

                $email = App::call()->usersRepository->getEmail($nbs_users[$j]['id']);
                if (is_array($email)) {
                    $email = implode(', ', $email);
                }
                $order = App::call()->ordersRepository->GetOrdersOne($proj_id);
                $subject = "Запрос на квотирование  проекта {$order['proj_id']}  {$order['project_name']}";
                $text = "Добрый день, В NBS Platform Вам поступил запрос на квотирование 
                <a href='http://crm.i-bios.com/poworksheets/quote/?id={$resultW}'>{$order['proj_id']} </a>
                {$order['project_name']}
                {$order['feas_russian']}
                
                ";

                $mailSend = new Mail();
                if (($email !== '') && !is_null($email)) {
                    $mailSend->email = $email;
                    $mailSend->subject = $subject;
                    $mailSend->text_mail = $text;
                    $mailSend->send_time = date('Y-m-d H:i:s');
                    $mailSend->send = 'NO';
                }

                App::call()->mailRepository->save($mailSend);
            }

        }


    }

    public function generateOnebyOneOrdersPrimat($proj_id)
    {

        $nbs_users = App::call()->usersRepository->getManagerPrimat();


        for ($j = 0, $jMax = count($nbs_users); $j < $jMax; $j++) {
            $result = 0;
            $worksheets = new Poworksheets();
            $worksheets->proj_id = $proj_id;
            $worksheets->user_id = $nbs_users[$j]['id'];
            $worksheets->status_id = 1;
            $resultW = $this->save($worksheets);

            if ($resultW > 0) {

                $email = App::call()->usersRepository->getEmail($nbs_users[$j]['id']);
                if (is_array($email)) {
                    $email = implode(', ', $email);
                }
                $order = App::call()->ordersRepository->GetOrdersOne($proj_id);
                $subject = "Запрос на квотирование проекта {$order['proj_id']}  {$order['project_name']}";
                $text = "Добрый день, В NBS Platform Вам поступил запрос на квотирование 
                <a href='http://crm.i-bios.com/worksheets/quote/?id={$resultW}'>{$order['proj_id']} </a>
                {$order['project_name']}
                {$order['feas_russian']}
                
                ";

                $mailSend = new Mail();
                if (($email !== '') && !is_null($email)) {
                    $mailSend->email = $email;
                    $mailSend->subject = $subject;
                    $mailSend->text_mail = $text;
                    $mailSend->send_time = date('Y-m-d H:i:s');
                    $mailSend->send = 'NO';
                }

                App::call()->mailRepository->save($mailSend);
            }

        }


    }


    public function getCountByProjectAndGroupUserName($proj_id)
    {
        $sql = "SELECT 
        * FROM po_worksheets
        INNER JOIN nbs_users ON nbs_users.id = po_worksheets.user_id
        WHERE status_id IN (3,1)
        AND po_worksheets.proj_id=:proj_id
        AND nbs_users.deleted != 1
        AND nbs_users.id NOT IN (37, 49, 59,  63, 13, 17, 13, 30)";

        $params = [
            'proj_id' => $proj_id
        ];
        return Db::getInstance()->queryAll($sql, $params);

    }


    public function getUserWorksheets($proj_id)
    {
        $sql = "SELECT 
        nbs_users.email FROM po_worksheets
        INNER JOIN nbs_users ON nbs_users.id = po_worksheets.user_id
        WHERE 
              -- status_id IN (3,1) AND
        po_worksheets.proj_id=:proj_id
        AND nbs_users.deleted != 1
        AND nbs_users.id NOT IN (37, 49, 59,  63, 13, 17, 13, 30)";

        $params = [
            'proj_id' => $proj_id
        ];
        return Db::getInstance()->queryAll($sql, $params);

    }



    public function getJoinPo()
    {
        $user_id = App::call()->session->getSession('user_id');
        $sql = "SELECT 
        po_worksheets.id,
        po_main_table.proj_id,
        po_main_table.fr_date, 
        po_main_table.internal_id, 
        po_main_table.external_id, 
        po_main_table.project_name, 
        po_main_table.project_details, 
        po_main_table.feas_russian, 
        po_main_table.comments, 
        po_main_table.deadline,
        po_main_table.po_number,
        nbs_worksheets_status.name as worksheets_status,
        po_worksheets.id as worksheets_id
        FROM po_main_table 
        INNER JOIN po_worksheets ON po_main_table.proj_id = po_worksheets.proj_id
        INNER JOIN nbs_worksheets_status ON nbs_worksheets_status.id = po_worksheets.status_id
        WHERE  po_worksheets.user_id=:user_id
        AND  po_main_table.po_status != 4 -- статус 4 - Проект закрыт
        ORDER by po_main_table.fr_date  DESC";
        $params = ['user_id' => $user_id];
        return Db::getInstance()->queryAll($sql, $params);
    }


    public function getJoinPoDepartment($department_id)
    {
        $sql = "SELECT 
        po_worksheets.id,
        po_main_table.proj_id,
        po_main_table.fr_date, 
        po_main_table.internal_id, 
        po_main_table.external_id, 
        po_main_table.project_name, 
        po_main_table.project_details, 
        po_main_table.feas_russian, 
        po_main_table.comments, 
        po_main_table.deadline,
        po_main_table.po_number,
        nbs_worksheets_status.name as worksheets_status,
        po_worksheets.id as worksheets_id
        FROM po_main_table 
        INNER JOIN po_worksheets ON po_main_table.proj_id = po_worksheets.proj_id
        INNER JOIN nbs_worksheets_status ON nbs_worksheets_status.id = po_worksheets.status_id
        INNER JOIN nbs_users ON nbs_users.id = po_worksheets.user_id
        WHERE  nbs_users.department_id=:department_id
        AND  po_main_table.po_status != 4 -- статус 4 - Проект закрыт
        ORDER by po_main_table.fr_date  DESC";
        $params = ['department_id' => $department_id];
        return Db::getInstance()->queryAll($sql, $params);
    }



    public function findWorksheet($user_id,$proj_id)
    {

        $sql = "SELECT 
        po_worksheets.id
        FROM po_worksheets 
        WHERE  po_worksheets.user_id= :user_id
        AND po_worksheets.proj_id = :proj_id
        LIMIT 1";
        $params = [
            'user_id' => $user_id,
            'proj_id' => $proj_id
            ];
        return Db::getInstance()->queryOne($sql, $params);
    }




}
