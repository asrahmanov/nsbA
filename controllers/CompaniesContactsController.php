<?php

namespace app\controllers;

use app\engine\App;
use app\models\entities\CompaniesContacts;


class CompaniesContactsController extends Controller
{

    protected $layout = 'main';
    protected $folder = 'CompaniesContacts/';
    protected $defaultAction = 'CompaniesContacts';

    public function actionCompaniesContacts()
    {
        echo $this->render($this->folder . $this->defaultAction);
    }

    public function actionGetAll ()
    {
        $companies_contacts_all = App::call()->companiesContactsRepository->getAll();
        $role_id = App::call()->session->getSession('role_id');
        $user_id = App::call()->session->getSession('user_id');
        if ($role_id !== '1' && $role_id !== '6' && $user_id !== '5') {
            foreach ($companies_contacts_all as $companies_contact) {
                $contact_is_mine = App::call()->managerSitesRepository->getCountWhere([
                    'user_id' => $user_id,
                    'site_id' => $companies_contact['company_id']
                ]);
                if ($contact_is_mine) {
                    $companies_contacts[] = $companies_contact;
                }
            }
        } else {
            $companies_contacts = $companies_contacts_all;
        }

        foreach ($companies_contacts as $key => $companies_contact) {
            $site = App::call()->sitesRepository->getOne($companies_contact['company_id']);
            $companies_contacts[$key]['site_name'] = $site['site_name'];
        }
        echo json_encode($companies_contacts);
    }

    public function actionGetSiteContactsBySiteId ()
    {
        if (isset(App::call()->request->getParams()['site_id'])) {
            $site_id = App::call()->request->getParams()['site_id'];
            $data = App::call()->companiesContactsRepository->getSiteContactsBySiteId($site_id);
            echo json_encode(['result' => $data]);
        } else {
            echo json_encode(['result' => false]);
        }
    }

    public function actionGetOne ()
    {
        $id = App::call()->request->getParams()['id'];
        $companiesContacts = App::call()->companiesContactsRepository->getOne($id);
        echo json_encode(['result' => $companiesContacts]);
    }

    public function actionDel ()
    {
        $id = App::call()->request->getParams()['id'];
        $companiesContacts = App::call()->companiesContactsRepository->getObject($id);
        $result = App::call()->companiesContactsRepository->delete($companiesContacts);
        echo json_encode(['result' => $result]);
    }

    public function actionSave()
    {
        if (isset(App::call()->request->getParams()['id']) && App::call()->request->getParams()['id']) {
            $id = App::call()->request->getParams()['id'];
            $contact = App::call()->companiesContactsRepository->getObject($id);
        } else {
            $contact = New CompaniesContacts();
        }

        if (isset(App::call()->request->getParams()['company_id'])) {
            $company_id = App::call()->request->getParams()['company_id'];
            $contact->company_id = $company_id;
        }
        if (isset(App::call()->request->getParams()['firstname'])) {
            $firstname = App::call()->request->getParams()['firstname'];
            $contact->firstname = $firstname;
        }
        if (isset(App::call()->request->getParams()['lastname'])) {
            $lastname = App::call()->request->getParams()['lastname'];
            $contact->lastname = $lastname;
        }
        if (isset(App::call()->request->getParams()['patronymic'])) {
            $patronymic = App::call()->request->getParams()['patronymic'];
            $contact->patronymic = $patronymic;
        }
        if (isset(App::call()->request->getParams()['contact_type'])) {
            $contact_type = App::call()->request->getParams()['contact_type'];
            $contact->contact_type = $contact_type;
        }
        if (isset(App::call()->request->getParams()['job'])) {
            $job = App::call()->request->getParams()['job'];
            $contact->job = $job;
        }
        if (isset(App::call()->request->getParams()['contact_source'])) {
            $contact_source = App::call()->request->getParams()['contact_source'];
            $contact->contact_source = $contact_source;
        }
        if (isset(App::call()->request->getParams()['work_phone'])) {
            $work_phone = App::call()->request->getParams()['work_phone'];
            $contact->work_phone = $work_phone;
        }
        if (isset(App::call()->request->getParams()['home_phone'])) {
            $home_phone = App::call()->request->getParams()['home_phone'];
            $contact->home_phone = $home_phone;
        }
        if (isset(App::call()->request->getParams()['other_phone'])) {
            $other_phone = App::call()->request->getParams()['other_phone'];
            $contact->other_phone = $other_phone;
        }
        if (isset(App::call()->request->getParams()['work_email'])) {
            $work_email = App::call()->request->getParams()['work_email'];
            $contact->work_email = $work_email;
        }
        if (isset(App::call()->request->getParams()['home_email'])) {
            $home_email = App::call()->request->getParams()['home_email'];
            $contact->home_email = $home_email;
        }
        if (isset(App::call()->request->getParams()['address'])) {
            $address = App::call()->request->getParams()['address'];
            $contact->address = $address;
        }
        if (isset(App::call()->request->getParams()['comment'])) {
            $comment = App::call()->request->getParams()['comment'];
            $contact->comment = $comment;
        }
        if (isset(App::call()->request->getParams()['nosology'])) {
            $nosology = App::call()->request->getParams()['nosology'];
            $contact->nosology = $nosology;
        }
        if (isset(App::call()->request->getParams()['active'])) {
            $active = App::call()->request->getParams()['active'];
            $contact->active = $active;
        }
        if (isset(App::call()->request->getParams()['civil_contract'])) {
            $civil_contract = App::call()->request->getParams()['civil_contract'];
            $contact->civil_contract = $civil_contract;
        }
        if (isset(App::call()->request->getParams()['newsletter'])) {
            $newsletter = App::call()->request->getParams()['newsletter'];
            $contact->newsletter = $newsletter;
        }
        if (isset(App::call()->request->getParams()['special_id'])) {
            $special_id = App::call()->request->getParams()['special_id'];
            $contact->special_id = $special_id;
        }
        if (isset(App::call()->request->getParams()['quotable'])) {
            $quotable = App::call()->request->getParams()['quotable'];
            $contact->quotable = $quotable;
        }

        if (isset(App::call()->request->getParams()['manager_point'])) {
            $manager_point = App::call()->request->getParams()['manager_point'];
            $contact->manager_point = $manager_point;
        }

        if (isset(App::call()->request->getParams()['order_point'])) {
            $order_point = App::call()->request->getParams()['order_point'];
            $contact->order_point = $order_point;
        }

        if (isset(App::call()->request->getParams()['nbs_point'])) {
            $nbs_point = App::call()->request->getParams()['nbs_point'];
            $contact->nbs_point = $nbs_point;
        }

        $user_id = App::call()->session->getSession('user_id');
        $contact->trigger_user_id = $user_id;

        $result = App::call()->companiesContactsRepository->save($contact);

        echo json_encode(['result' => $result]);

    }

}
