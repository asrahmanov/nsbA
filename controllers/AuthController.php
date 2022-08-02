<?php


namespace app\controllers;

use app\controllers\SessionController;
use app\engine\App;
use app\models\entities\Users;

class AuthController extends Controller
{
    protected $defaultAction = 'login';
    protected $layout = 'auth';

    public function actionLogin()
    {
        echo $this->render($this->defaultAction);
        $this->layout = 'auth';
    }


    public function actionLogout()
    {
        App::call()->session->sessionDestroy();
        header('Location: /auth');

    }

    public function actionEntrance()
    {
        $login = App::call()->request->getParams()['login'];
        $password = App::call()->request->getParams()['password'];
        $user = App::call()->usersRepository->auth($login, $password);

        if (!$user) {
            echo json_encode(['response' => false]);
        } else {
            echo json_encode(['response' => true]);
        }
        die();
    }

    public function actionEntranceAdmin()
    {
        $user_id= App::call()->request->getParams()['user_id'];
        $users = App::call()->usersRepository->getOne($user_id);
        $login = $users['login'];
        $password = $users['password'];
        $user = App::call()->usersRepository->authAdmin($login, $password);

        if (!$user) {
            echo json_encode(['response' => false]);
        } else {
            echo json_encode(['response' => true]);
        }
        die();
    }


    public function actionProfile()
    {
        $this->layout = 'main';


        $user_id = App::call()->session->getSession('user_id');
        $user = App::call()->usersRepository->getOne($user_id);

       echo $this->render('profile',[
           'users' => $user

       ] );

    }




    public function actionUpdateColor() {

        $user_id= App::call()->sessionsession->getSession('user_id');
        $user = App::call()->usersRepository->getObject($user_id);
        $color = App::call()->request->getParams()['color'];
        $user->color = $color;
        App::call()->usersRepository->save($user);

    }

    public function actionUpdateBg() {

        $user_id= App::call()->session->getSession('user_id');
        $user = App::call()->usersRepository->getObject($user_id);
        $img_bg = App::call()->request->getParams()['img_bg'];
        $user->img_bg = $img_bg;
        App::call()->usersRepository->save($user);

    }




}
