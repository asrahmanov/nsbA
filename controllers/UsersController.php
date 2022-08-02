<?php


namespace app\controllers;

use app\controllers\SessionController;
use app\engine\App;
use app\models\entities\TableViews;
use app\models\entities\Users;

class UsersController extends Controller
{
    protected $defaultAction = 'users';

    public function actionUsers()
    {
        $role = App::call()->session->getSession('role_id');

        echo $this->render($this->defaultAction, [
            'role' => $role
        ]);

    }

    public function actionInfo()
    {


        $sites = [];

        $fr_sites = App::call()->sitesRepository->getAll();
        for ($i = 0; $i < count($fr_sites); $i++) {
            $sites[] = $fr_sites[$i];
        }

        $departments = App::call()->departmentsRepository->getAll();

        $role = App::call()->session->getSession('role_id');
        $user_id = App::call()->session->getSession('user_id');
        $id = App::call()->request->getParams()['id'];
        $user = App::call()->usersRepository->getOne($id);
        $roles = App::call()->roleRepository->getAll();
        echo $this->render('usersInfo', [
            'role' => $role,
            'roles' => $roles,
            'users' => $user,
            'sites' => $sites,
            'user_id' => $user_id,
            'departments' => $departments
        ]);

    }


    public function actionAdd()
    {
        $role = App::call()->session->getSession('role_id');
        $roles = App::call()->roleRepository->getAll();
        $departments = App::call()->departmentsRepository->getAll();
        echo $this->render('usersAdd', [
            'role' => $role,
            'roles' => $roles,
            'departments' => $departments
        ]);

    }

    public function actionGetviews()
    {
        $table_name = App::call()->request->getParams()['table_name'];
        $array = App::call()->tableViewsRepository->getTableView($table_name);
        $view = [];
        for ($i = 0; $i < count($array); $i++) {
            $view[] = $array[$i];
        }
        echo json_encode($view);
    }


    public function actionGetAll()
    {

        $nbs_users = App::call()->usersRepository->getAll();
        $nbs_role = App::call()->roleRepository->getAll();
        $nbs_departments = App::call()->departmentsRepository->getAll();

        $departments= [];
        for ($i = 0; $i < count($nbs_departments); $i++) {
            $departments[$nbs_departments[$i]['id']] = $nbs_departments[$i]['department_name'];
        }

        $role = [];
        for ($i = 0; $i < count($nbs_role); $i++) {
            $role[$nbs_role[$i]['id']] = $nbs_role[$i]['name'];
        }

        for ($i = 0; $i < count($nbs_users); $i++) {
            $json['data'][$i]['id'] = $nbs_users[$i]['id'];
            $json['data'][$i]['lasttname'] = $nbs_users[$i]['lasttname'];
            $json['data'][$i]['firstname'] = $nbs_users[$i]['firstname'];
            $json['data'][$i]['patronymic'] = $nbs_users[$i]['patronymic'];
            $json['data'][$i]['deleted'] = $nbs_users[$i]['deleted'];
            $json['data'][$i]['login'] = $nbs_users[$i]['login'];
            $json['data'][$i]['role_id'] = $role[$nbs_users[$i]['role_id']];
            $json['data'][$i]['department'] = $departments[$nbs_users[$i]['department_id']];
        }

        echo json_encode($json);

    }

    public function actionGetEverybody()
    {
        $nbs_users = App::call()->usersRepository->getEverybody();
        $nbs_role = App::call()->roleRepository->getAll();

        $role = [];
        for ($i = 0; $i < count($nbs_role); $i++) {
            $role[$nbs_role[$i]['id']] = $nbs_role[$i]['name'];
        }

        for ($i = 0; $i < count($nbs_users); $i++) {
            $json['data'][$i]['id'] = $nbs_users[$i]['id'];
            $json['data'][$i]['lasttname'] = $nbs_users[$i]['lasttname'];
            $json['data'][$i]['firstname'] = $nbs_users[$i]['firstname'];
            $json['data'][$i]['patronymic'] = $nbs_users[$i]['patronymic'];
            $json['data'][$i]['login'] = $nbs_users[$i]['login'];
            $json['data'][$i]['role_id'] = $role[$nbs_users[$i]['role_id']];
            $json['data'][$i]['deleted'] = $nbs_users[$i]['deleted'];
        }

        echo json_encode($json);

    }


    public function actionViewsave()
    {

        $table_name = App::call()->request->getParams()['table_name'];
        $column_name = App::call()->request->getParams()['column_name'];
        $checked = App::call()->request->getParams()['checked'];

        if ($checked == "true") {
            $checked = 1;
        } else {
            $checked = 0;
        }


        $user_id = App::call()->session->getSession('user_id');


        $check = App::call()->tableViewsRepository->getViewOne($table_name, $column_name);
        if (count($check)) {
            $view = App::call()->tableViewsRepository->getObject($check[0]['id']);
            $view->checkeds = $checked;

        } else {
            $view = new TableViews();

            $view->user_id = $user_id;
            $view->table_name = $table_name;
            $view->column_name = $column_name;
            $view->checkeds = $checked;
        }

        $result = App::call()->tableViewsRepository->save($view);
        echo $result;


    }


    public function actionSave()
    {

        if (isset(App::call()->request->getParams()['id'])) {
            $id = App::call()->request->getParams()['id'];
            $user = App::call()->usersRepository->getObject($id);

        } else {
            $user = New Users();
        }



        if (isset(App::call()->request->getParams()['role_id'])) {
            $role_id = App::call()->request->getParams()['role_id'];
            $user->role_id = $role_id;
        }

        if (isset(App::call()->request->getParams()['password'])) {
            $password = App::call()->request->getParams()['password'];
            $password = password_hash($password, PASSWORD_DEFAULT);
            $user->password = $password;
        }

        if (isset(App::call()->request->getParams()['deleted'])) {
            $deleted = App::call()->request->getParams()['deleted'];
            $user->deleted = $deleted;
        }

        if (isset(App::call()->request->getParams()['firstname'])) {
            $firstname = App::call()->request->getParams()['firstname'];
            $user->firstname = $firstname;
        }

        if (isset(App::call()->request->getParams()['lasttname'])) {
            $lasttname = App::call()->request->getParams()['lasttname'];
            $user->lasttname = $lasttname;
        }

        if (isset(App::call()->request->getParams()['patronymic'])) {
            $patronymic = App::call()->request->getParams()['patronymic'];
            $user->patronymic = $patronymic;
        }

        if (isset(App::call()->request->getParams()['login'])) {
            $login = App::call()->request->getParams()['login'];
            $user->login = $login;
        }

        if (isset(App::call()->request->getParams()['email'])) {
            $email = App::call()->request->getParams()['email'];
            $user->email = $email;
        }


        if (isset(App::call()->request->getParams()['department_id'])) {
            $department_id = App::call()->request->getParams()['department_id'];
            $user->department_id = $department_id;
        }


        $result = App::call()->usersRepository->save($user);
        echo $result;
    }


    public function actionChangePassword()
    {

        $user_id = App::call()->session->getSession('user_id');
        $password_old = App::call()->request->getParams()['password_old'];
        $password_new = App::call()->request->getParams()['password_new'];
        $user = App::call()->usersRepository->getObject($user_id);
        $result = password_verify($password_old, $user->password);

        if ($result) {
            $user->password = password_hash($password_new, PASSWORD_DEFAULT);
            App::call()->usersRepository->save($user);
            echo json_encode(['result' => 1]);
        } else {
            echo json_encode(['result' => 0]);
        }

    }

    // Функция сбороса пароля
    public function actionReset()
    {
        $user_id = App::call()->request->getParams()['id'];
        $user = App::call()->usersRepository->getObject($user_id);
        $password_new = App::call()->usersRepository->randomPassword();
        $user->password = password_hash($password_new, PASSWORD_DEFAULT);
        App::call()->usersRepository->save($user);

        $email = $user->email;
        $subject = 'Востановление пароля';
        $text = "Ваш новый пароль: $password_new";
        $resulty_send_nail = App::call()->mailRepository->sendMail($email, $subject, $text);

        echo json_encode(['result' => $resulty_send_nail]);

    }


    public function actionGetManagerList()
    {
        $nbs_users = App::call()->usersRepository->getManager();
        for ($j = 0; $j < count($nbs_users); $j++) {
            echo "--->" . $nbs_users[$j]['id'] . '<br>';
        }

    }

    public function actionGetUsersByRole()
    {
        $role = App::call()->request->getParams()['role'];
        $users = App::call()->usersRepository->GetUsersByRole($role);
        echo json_encode(['result' => $users]);

    }

    public function actionGetManagers()
    {
        $managers = App::call()->usersRepository->GetUsersByRole(3);
        $high_managers = App::call()->usersRepository->GetUsersByRole(8);
        $users = array_merge($managers, $high_managers);
        echo json_encode(['result' => $users]);

    }




}
