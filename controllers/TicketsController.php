<?php

namespace app\controllers;

use app\engine\App;
use app\models\entities\Tickets;


class TicketsController extends Controller
{
    protected $layout = 'main';
    protected $folder = 'tickets/';
    protected $defaultAction = 'index';

    public function actionIndex()
    {
        $department_id = App::call()->session->getSession('department_id');
        $role_id = App::call()->session->getSession('role_id');
        echo $this->render($this->folder . $this->defaultAction, [
            'department_id' => $department_id,
            'role_id' => $role_id
        ]);
    }

    public function actionGetTicketsByDepartment()
    {
        $department_id = App::call()->session->getSession('department_id');
        $table = App::call()->ticketsRepository->getWhere(['department_id' => $department_id]);

        $users = [];
        $nbs_users = App::call()->usersRepository->getAll();
        for ($i = 0; $i < count($nbs_users); $i++) {
            $users[$nbs_users[$i]['id']] = $nbs_users[$i]['lasttname'] . ' ' . $nbs_users[$i]['firstname'] . ' ' . $nbs_users[$i]['patronymic'];
        }


        for ($i = 0; $i < count($table); $i++) {
            $json['data'][$i]['mail_date'] = $table[$i]['mail_date'];
            $json['data'][$i]['client_identifier'] = $table[$i]['client_identifier'];
            $json['data'][$i]['task'] = $table[$i]['task'];
            $json['data'][$i]['reply'] = $table[$i]['reply'];
            $json['data'][$i]['created_at'] = $table[$i]['created_at'];
            $json['data'][$i]['updated_at'] = $table[$i]['updated_at'];
            $json['data'][$i]['status'] = $table[$i]['status'];


            if (isset($users[$table[$i]['create_user_id']])) {
                $json['data'][$i]['create_user_id'] = $users[$table[$i]['create_user_id']];
            } else {
                $json['data'][$i]['create_user_id'] = 'Не закреплен';
            }

            if (isset($users[$table[$i]['update_user_id']])) {
                $json['data'][$i]['update_user_id'] = $users[$table[$i]['update_user_id']];
            } else {
                $json['data'][$i]['update_user_id'] = 'Не закреплен';
            }

        }

        echo json_encode($json);
    }

    public function actionGetTickets()
    {
        $table = App::call()->ticketsRepository->getAll();

        $users = [];
        $nbs_users = App::call()->usersRepository->getAll();
        for ($i = 0; $i < count($nbs_users); $i++) {
            $users[$nbs_users[$i]['id']] = $nbs_users[$i]['lasttname'] . ' ' . $nbs_users[$i]['firstname'] . ' ' . $nbs_users[$i]['patronymic'];
        }


        for ($i = 0; $i < count($table); $i++) {
            $json['data'][$i]['id'] = $table[$i]['id'];
            $json['data'][$i]['mail_date'] = $table[$i]['mail_date'];
            $json['data'][$i]['client_identifier'] = $table[$i]['client_identifier'];
            $json['data'][$i]['task'] = $table[$i]['task'];
            $json['data'][$i]['reply'] = $table[$i]['reply'];
            $json['data'][$i]['created_at'] = $table[$i]['created_at'];
            $json['data'][$i]['updated_at'] = $table[$i]['updated_at'];
            $json['data'][$i]['status'] = $table[$i]['status'];

            if (isset($users[$table[$i]['create_user_id']])) {
                $json['data'][$i]['create_user_id'] = $users[$table[$i]['create_user_id']];
            } else {
                $json['data'][$i]['create_user_id'] = 'Не закреплен';
            }

            if (isset($users[$table[$i]['update_user_id']])) {
                $json['data'][$i]['update_user_id'] = $users[$table[$i]['update_user_id']];
            } else {
                $json['data'][$i]['update_user_id'] = 'Не закреплен';
            }

        }

        echo json_encode($json);
    }


    private function isFromMyDepartment($ticket)
    {
        $department_id = App::call()->session->getSession('department_id');
        return $ticket['department_id'] == $department_id;
    }

//        if (isset(App::call()->request->getParams()['site_id'])) {
//            $site_id = App::call()->request->getParams()['site_id'];
//            $data = App::call()->companiesContactsRepository->getSiteContactsBySiteId($site_id);
//            echo json_encode(['result' => $data]);
//        } else {
//            echo json_encode(['result' => false]);
//        }
//    }
//
//    public function actionGetOne ()
//    {
//        $id = App::call()->request->getParams()['id'];
//        $companiesContacts = App::call()->companiesContactsRepository->getOne($id);
//        echo json_encode(['result' => $companiesContacts]);
//    }
//
//    public function actionDel ()
//    {
//        $id = App::call()->request->getParams()['id'];
//        $companiesContacts = App::call()->companiesContactsRepository->getObject($id);
//        $result = App::call()->companiesContactsRepository->delete($companiesContacts);
//        echo json_encode(['result' => $result]);
//    }
//
    public function actionSave()
    {
        $user_id = App::call()->session->getSession('user_id');
        if (isset(App::call()->request->getParams()['id'])) {
            $id = App::call()->request->getParams()['id'];
            $ticket = App::call()->ticketsRepository->getObject($id);
            $ticket->update_user_id = $user_id;
        } else {
            $ticket = new Tickets();
            $ticket->create_user_id = $user_id;
            $ticket->done = '0';
        }

        if (isset(App::call()->request->getParams()['client_identifier'])) {
            $client_identifier = App::call()->request->getParams()['client_identifier'];
            $ticket->client_identifier = $client_identifier;
        }
        if (isset(App::call()->request->getParams()['mail_date'])) {
            $mail_date = App::call()->request->getParams()['mail_date'];
            $ticket->mail_date = $mail_date;
        }
        if (isset(App::call()->request->getParams()['task'])) {
            $task = App::call()->request->getParams()['task'];
            $ticket->task = $task;
        }
        if (isset(App::call()->request->getParams()['reply'])) {
            $reply = App::call()->request->getParams()['reply'];
            $ticket->reply = $reply;
        }
        if (isset(App::call()->request->getParams()['department_id'])) {
            $department_id = App::call()->request->getParams()['department_id'];
            $ticket->department_id = $department_id;
        }
        if (isset(App::call()->request->getParams()['author_id'])) {
            $author_id = App::call()->request->getParams()['author_id'];
            $ticket->author_id = $author_id;
        }
        if (isset(App::call()->request->getParams()['role_id'])) {
            $role_id = App::call()->request->getParams()['role_id'];
            $ticket->role_id = $role_id;
        }
        if (isset(App::call()->request->getParams()['worker_id'])) {
            $worker_id = App::call()->request->getParams()['worker_id'];
            $ticket->worker_id = $worker_id;
        }
        if (isset(App::call()->request->getParams()['status'])) {
            $status = App::call()->request->getParams()['status'];
            $ticket->status = $status;
        }

        if (isset(App::call()->request->getParams()['deleted'])) {
            $deleted = App::call()->request->getParams()['deleted'];
            $ticket->deleted = $deleted;
        }

        $result = App::call()->ticketsRepository->save($ticket);

        echo json_encode(['result' => $result]);

    }

}
