<?php

namespace app\controllers;

use app\engine\App;
use app\engine\Request;
use app\engine\Session;
use app\interfaces\IRenderer;
use app\models\repositories\UsersRepository;
use phpDocumentor\Reflection\Location;

class Controller implements IRenderer
{
    protected $defaultAction = 'index';
    private $action;
    protected $layout = 'main';
    private $userLayout = true;
    private $render;
    private $renderer;
    public $request;

    public function __construct(IRenderer $renderer)
    {
        $this->render = $renderer;
        $this->request = new Request();
    }


    public function runAction($action = null)
    {
        $this->action = $action ?: $this->defaultAction;
        $method = "action" . ucfirst($this->action);
        if (method_exists($this, $method)) {
            $this->$method();
        } else {
            var_dump($method);
            echo "404 action";
        }
    }


    public function actionIndex()
    {
        $role = App::call()->session->getSession('role_id');
        $user_id = App::call()->session->getSession('user_id');
        if ($role == '3' or $role == '8') {
            $this->layout = 'vue';
            echo $this->render('worksheets/worksheets', [
                user_id => $user_id
            ]);
        } else if ($role == '4' or $role == '5' or $role == '9') {

            header('Location: /sites');
        } else if ($role == '6') {
            header('Location: /pages');
        } else if ($role == '7') {
            header('Location: /ClinicalCase');
        } else if ($role != '') {
            header('Location: /orders');
        } else {
            header('Location: /auth');
        }
    }


    public function render($template, $params = [])
    {
        if ($this->layout == 'api') {
            return $this->renderTemplate("layouts/api", [
                'menu' => $this->renderTemplate('apimenu', [
                ]),
                'top' => $this->renderTemplate('top', [
                ]),
                'footer' => $this->renderTemplate('footer', [
                    'footer_date' => date('Y')
                ]),
                'content' => $this->renderTemplate($template, $params),
            ]);
        }


        if ($this->userLayout) {
            $role_id = App::call()->session->getSession('role_id');
            $department_id = App::call()->session->getSession('department_id');
            $menu = App::call()->rightsMenuRepository->getMenu($role_id);
            $firstname = App::call()->session->getSession('firstname');
            $lasttname = App::call()->session->getSession('lasttname');
            $patronymic = App::call()->session->getSession('patronymic');
            $user_id = App::call()->session->getSession('user_id');

            $theme = App::call()->usersRepository->getThemeUsers($user_id);

            $countPriority = App::call()->ordersRepository->getOrdersMyPriority();


            // Проверка имеет ли данный роль доступ к этому template

            if ($template != 'login' and $template != 'api/info' and $template != 'api/info/') {
                $access = App::call()->accessRepository->checkAccess($template, $role_id);
                if ($access === '0') {
                    if (
                        ($template == 'invertory/invertory' and $department_id == 3)
                        or ($template == 'laboratory/laboratory' and $department_id == 4)
                        or ($template == 'orders/dashboard' and $user_id == 5)
                        or ($template == 'ClinicalCase/ClinicalCase' and $user_id == 9)
                        or ($template == 'OfferApproval/OfferApproval' and $user_id == 56)
                        or ($template == 'ClinicalCase/ClinicalCaseManager' and $user_id == 25)
                        or ($template == 'ClinicalCase/ClinicalCaseManager' and $user_id == 23)
                        or ($template == 'poworksheets/Quote' and $user_id == 25)
                        or ($template == 'poworksheets/Quote' and $user_id == 23)
                        or ($template == 'poworksheets/Poworksheets' and $user_id == 25)
                        or ($template == 'poworksheets/Poworksheets' and $user_id == 23)
                        or ($template == 'po/poInfo' and $user_id == 25)
                        or ($template == 'po/poInfo' and $user_id == 23)
                        or ($template == 'po/pomanager' and $user_id == 25)
                        or ($template == 'siteCapability/manager' and $user_id == 25)
                        or ($template == 'siteCapability/managerEdit' and $user_id == 25)
                        or ($template == 'po/pomanager' and $user_id == 23)
                        or ($template == 'siteCapability/manager' and $user_id == 23)
                        or ($template == 'siteCapability/managerEdit' and $user_id == 23)

                    ) {

                    } else if ($template == 'api/info' or $template == 'api/info/') {
                        return $this->renderTemplate($template, $params);

                    } else {
                        return $this->renderTemplate('404', $params = []);
                    }
                }
            }


            if ($department_id == 3) {
                $menu[] = [
                    'id' => '666',
                    'role_id' => '',
                    'name' => 'Project department',
                    'alias' => 'invertory',
                    'icon' => 'ft-briefcase',
                    'created_at' => '2020-07-09 16:33:47',
                    'deleted' => null,
                    'parent_id' => '0'];
            }

            if ($department_id == 4) {
                $menu[] = [
                    'id' => '666',
                    'role_id' => '',
                    'name' => 'LTO',
                    'alias' => 'laboratory',
                    'icon' => 'ft-briefcase',
                    'created_at' => '2020-07-09 16:33:47',
                    'deleted' => null,
                    'parent_id' => '0'];

                $menu[] = [
                    'id' => '667',
                    'role_id' => '',
                    'name' => 'SITES',
                    'alias' => 'sites',
                    'icon' => 'ft-grid',
                    'created_at' => '2020-07-09 16:33:47',
                    'deleted' => null,
                    'parent_id' => '0'];
            }

            if ($department_id == 3 || $department_id == 4) {
                $menu[] = [
                    'id' => '668',
                    'role_id' => '',
                    'name' => 'TICKETS',
                    'alias' => 'tickets',
                    'icon' => 'ft-grid',
                    'created_at' => '2020-08-22 20:14:47',
                    'deleted' => null,
                    'parent_id' => '0'
                ];
            }
            if ($user_id == 5) {
                $menu[] = [
                    'id' => '669',
                    'role_id' => '',
                    'name' => 'DASHBOARD',
                    'alias' => 'dashboard',
                    'icon' => 'ft-grid',
                    'created_at' => '2020-09-16 20:14:47',
                    'deleted' => null,
                    'parent_id' => '0'
                ];
            }

            if ($user_id == 9) {
                $menu[] = [
                    'id' => '711',
                    'role_id' => '',
                    'name' => 'ClinicalCase',
                    'alias' => 'ClinicalCase',
                    'icon' => 'ft-grid',
                    'created_at' => '2020-09-16 20:14:47',
                    'deleted' => null,
                    'parent_id' => '0'
                ];
            }

            if ($user_id == 25) {
                $menu[] = [
                    'id' => '713',
                    'role_id' => '',
                    'name' => 'ClinicalCase',
                    'alias' => 'ClinicalCase/ClinicalCaseManager',
                    'icon' => 'ft-grid',
                    'created_at' => '2020-09-16 20:14:47',
                    'deleted' => null,
                    'parent_id' => '0'
                ];

                $menu[] = [
                    'id' => '714',
                    'role_id' => '',
                    'name' => 'PWORKSHEETS',
                    'alias' => 'poWorksheets',
                    'icon' => 'ft-grid',
                    'created_at' => '2020-09-16 20:14:47',
                    'deleted' => null,
                    'parent_id' => '0'
                ];
                $menu[] = [
                    'id' => '715',
                    'role_id' => '',
                    'name' => 'PMANAGER',
                    'alias' => 'Po/pomanager',
                    'icon' => 'ft-grid',
                    'created_at' => '2020-09-16 20:14:47',
                    'deleted' => null,
                    'parent_id' => '0'
                ];
                $menu[] = [
                    'id' => '800',
                    'role_id' => '',
                    'name' => 'MSC',
                    'alias' => 'ManagerSitecapability',
                    'icon' => 'ft-grid',
                    'created_at' => '2020-09-16 20:14:47',
                    'deleted' => null,
                    'parent_id' => '0'
                ];

            }

            if ($user_id == 23) {
                $menu[] = [
                    'id' => '713',
                    'role_id' => '',
                    'name' => 'ClinicalCase',
                    'alias' => 'ClinicalCase/ClinicalCaseManager',
                    'icon' => 'ft-grid',
                    'created_at' => '2020-09-16 20:14:47',
                    'deleted' => null,
                    'parent_id' => '0'
                ];
                $menu[] = [
                    'id' => '714',
                    'role_id' => '',
                    'name' => 'PWORKSHEETS',
                    'alias' => 'poWorksheets',
                    'icon' => 'ft-grid',
                    'created_at' => '2020-09-16 20:14:47',
                    'deleted' => null,
                    'parent_id' => '0'
                ];
                $menu[] = [
                    'id' => '715',
                    'role_id' => '',
                    'name' => 'PMANAGER',
                    'alias' => 'Po/pomanager',
                    'icon' => 'ft-grid',
                    'created_at' => '2020-09-16 20:14:47',
                    'deleted' => null,
                    'parent_id' => '0'
                ];
                $menu[] = [
                    'id' => '800',
                    'role_id' => '',
                    'name' => 'MSC',
                    'alias' => 'ManagerSitecapability',
                    'icon' => 'ft-grid',
                    'created_at' => '2020-09-16 20:14:47',
                    'deleted' => null,
                    'parent_id' => '0'
                ];
            }

            if ($user_id == 56) {
                $menu[] = [
                    'id' => '712',
                    'role_id' => '',
                    'name' => 'OfferApproval',
                    'alias' => 'OfferApproval',
                    'icon' => 'ft-grid',
                    'created_at' => '2020-09-16 20:14:47',
                    'deleted' => null,
                    'parent_id' => '0'
                ];
            }


            // TODO Удаление из меню проектов для роли менеджера где они должны быть
            if ($user_id == 95 or $user_id == 91 or $user_id == 96) {
                for ($i = 0; $i < count($menu); $i++) {
                    if ($menu[$i]['name'] == 'PWORKSHEETS') {
                        unset($menu[$i]);
                        sort($menu);
                    }
                }
            }



            if ($user_id == 95 or $user_id == 91 or $user_id == 96) {
                for ($i = 0; $i < count($menu); $i++) {
                    if ($menu[$i]['name'] == 'Personal Tasks') {
                        unset($menu[$i]);
                        sort($menu);
                    }
                }
            }



            if ($user_id == 95 or $user_id == 91 or $user_id == 96) {
                for ($i = 0; $i < count($menu); $i++) {
                    if ($menu[$i]['name'] == 'VACATION') {
                        unset($menu[$i]);
                        sort($menu);
                    }
                }
            }


            if ($user_id == 95 or $user_id == 91 or $user_id == 96) {
                for ($i = 0; $i < count($menu); $i++) {
                    if ($menu[$i]['name'] == 'My ClinicalCase') {
                        unset($menu[$i]);
                        sort($menu);
                    }
                }
            }


            if ($user_id == 95 or $user_id == 91 or $user_id == 96) {
                for ($i = 0; $i < count($menu); $i++) {
                    if ($menu[$i]['name'] == 'SITES') {
                        unset($menu[$i]);
                        sort($menu);
                    }
                }
            }


            if ($user_id == 95 or $user_id == 91 or $user_id == 96) {
                for ($i = 0; $i < count($menu); $i++) {
                    if ($menu[$i]['name'] == 'MSC') {
                        unset($menu[$i]);
                        sort($menu);
                    }
                }
            }


            if ($user_id == 95 or $user_id == 91 or $user_id == 96) {
                for ($i = 0; $i < count($menu); $i++) {
                    if ($menu[$i]['name'] == 'FR TABLE') {
                        unset($menu[$i]);
                        sort($menu);
                    }
                }
            }


            // TODO Удаление из меню проектов для роли менеджера где они должны быть
            if ($user_id == 95 or $user_id == 91 or $user_id == 96) {
                for ($j = 0; $j < count($menu); $j++) {


                    if ($menu[$j]['name'] == 'PMANAGER') {
                        unset($menu[$j]);

                    }

                }
            }



            $menu_hierarchy = [];
            foreach ($menu as $i => $item) {
                $menu[$i]['children'] = [];
                if ($item['parent_id'] === '0') {
                    $menu_hierarchy[] = $menu[$i];
                }
            }
            foreach ($menu as $i => $item) {
                $menu['parent_id'] = array_key_exists('parent_id', $menu) ? $menu['parent_id'] : 0;
                if ($menu['parent_id'] !== '0') {
                    foreach ($menu_hierarchy as $j => $parent_item) {
                        if ($item['parent_id'] === $parent_item['id']) {
                            $menu_hierarchy[$j]['children'][] = $item;
                        }
                    }
                }
            }


            $iHaveNewTickets = false;
            $iHaveNewReminders = false;
            $array = App::call()->newTicketsRepository->getWhere(['author_id' => $user_id]);
            $in_array = App::call()->newTicketsRepository->getWhere(['target_id' => $user_id]);
            $in_array = array_merge($array, $in_array);
            foreach ($in_array as $i => $ticket) {
                if (!$iHaveNewTickets) {
                    $chat = App::call()->newTicketChatsRepository->getWhere(['ticket_id' => $ticket['id']]);
                    if (!$ticket['done'] && !$chat && $ticket['target_id'] == $user_id) {
                        $iHaveNewTickets = true;
                        if ($ticket['order_id']) {
                            $iHaveNewReminders = $ticket;
                        }
                    } else if (!$ticket['done'] && $chat) {
                        $chatlength = count($chat) - 1;
                        $lastmsg = $chat[$chatlength];
                        if ($lastmsg['author_id'] != $user_id/* && !$lastmsg['viewed']*/) {
                            $iHaveNewTickets = true;
                            if ($ticket['order_id']) {
                                $iHaveNewReminders = true;
                            }
                        }
                    }
                }
            }


            $dashbord = '';
            if ($role_id === '3') {
                $open = 0;
                $unanswered = 0;
                $answered = 0;
                $closedMonthly = 0;
                $totalScoreMonthly = 0;
                $myTickets = App::call()->newTicketsRepository->getWhere(['target_id' => $user_id]);
                $current_month = intval(date('m'));
                $quarter_months = [];
                if ($current_month >= 1 && $current_month <= 3)
                    $quarter_months = ['1', '2', '3'];
                else if ($current_month >= 4 && $current_month <= 6)
                    $quarter_months = ['4', '5', '6'];
                else if ($current_month >= 7 && $current_month <= 9)
                    $quarter_months = ['7', '8', '9'];
                else if ($current_month >= 10 && $current_month <= 12)
                    $quarter_months = ['10', '11', '12'];
                $max = 0;
                $efficiency = 0;

                foreach ($myTickets as $ticket) {
                    $date = date("Y-m-d", strtotime($ticket['created_at']));
                    // проверка входит ли дата создания тикета  в отпуск
                    $check = App::call()->vacationRepository->checkVacation($ticket['created_at']);
                    if ($check[0]["total"] === '0') {
                        if ($ticket['done'] !== '1') {
                            $open++;
                            $chat = App::call()->newTicketChatsRepository->getCountMassageGroupTicket($ticket['id']);
                            if ($chat[0]['total'] > 0) {
                                $answered++;
                            }
                        } else if (date('Y', strtotime($ticket['closed_at'])) === date('Y') &&
                            array_search(date('m', strtotime($ticket['closed_at'])), $quarter_months) !== false) {
                            $score = intval($ticket['score']);
                            if ($score)
                                $totalScoreMonthly += $score;
                            $closedMonthly++;
                            $max += 25;
                        }
                    }
                }
                $unanswered = $open - $answered;
                if ($max > 0)
                    $efficiency = intval($totalScoreMonthly / $max * 100);

                if ($user_id == 95 or $user_id == 91 or $user_id == 96) {
                } else {
                    $dashbord = "   <div class='row'>
                        <div class='col-md-6'>
                            <div class='card gradient-blackberry'>
                                <div class='card-content'>
                                    <div class='card-body pt-2 pb-0'>
                                        <div class='media' style='margin: 10px;'>
                                            <div class='media-body white text-left'>
                                           
                                                <h5>Персональные задачи </h5>
                                                <span>Неотвеченных: $unanswered</span><br>
                                                <span>В работе: $open</span>
                                            </div>
                                            <div class='media-right white text-right'>
                                                <i class='icon-pie-chart font-large-1'></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class='col-md-6'>
                            <div class='card gradient-ibiza-sunset'>
                                <div class='card-content'>
                                    <div class='card-body pt-2 pb-0'>
                                        <div class='media' style='margin: 10px;'>
                                            <div class='media-body white text-left'>
                                               <h5>Статистика</h5>
                                                 <span>Баллы: $totalScoreMonthly</span><br>
                                                <span>Закрыто: $closedMonthly</span><br>
                                                <span>Эффективность: $efficiency%</span>
                                            </div>
                                            <div class='media-right white text-right'>
                                                <i class='icon-bulb font-large-1'></i>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>";
                }


            }

            return $this->renderTemplate("layouts/{$this->layout}", [
                'menu' => $this->renderTemplate('menu', [
                    'userMenu' => $menu_hierarchy,
                    'color' => $theme['color'],
                    'img_bg' => $theme['img_bg'],
                    'iHaveNewTickets' => $iHaveNewTickets,
                    'iHaveNewReminders' => $iHaveNewReminders
                ]),
                'top' => $this->renderTemplate('top', [
                    'username' => $firstname,
                    'priority' => $countPriority['total'],
                    'dashbord' => $dashbord
                ]),
                'footer' => $this->renderTemplate('footer', [
                    'footer_date' => date('Y')
                ]),
                'content' => $this->renderTemplate($template, $params),
                'dashbord' => $dashbord,
                'auth' => App::call()->usersRepository->isAuth(),
                'username' => $firstname,
                'user_id' => $user_id
            ]);

        } else {
            return $this->renderTemplate($template, $params = []);
        }

    }

    public function renderTemplate($template, $params = [])
    {
        return $this->render->renderTemplate($template, $params);
    }


}
