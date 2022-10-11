<?php
// src/Controller/SubscriptionsController.php

namespace App\Controller;
use Cake\Filesystem\Folder;
use Cake\Filesystem\File;
use Cake\I18n\FrozenTime;
use Cake\Http\Client;

class SubscriptionsController extends AppController
{

    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        parent::beforeFilter($event);
        if (isset($_SESSION["Auth"])) {
            if ($_SESSION["Auth"]->role == 'root') {
                $this->Auth->allow();
            } elseif ($_SESSION["Auth"]->role == 'admin') {
                $this->Auth->allow();
            } else {
                $this->Auth->deny();
            }
        }
    }

    public function index($from="")
    {
        $this->set('role', $_SESSION["Auth"]->role);
        $mollie = $this->loadModel('Mollie');
        $list_customers = array();
        $customers = $mollie->get_customers();
        //var_dump($customers);
        foreach ($customers as $customer) {
            $list_customers[$customer['id']] = $customer;
        }
        $subscriptions = $mollie->get_all_subscriptions($from);
        if (isset($subscriptions['_links']['next'])) {
            $href= $subscriptions['_links']['next']['href'];
            if (preg_match('/from=(\w+)/', $href, $matches)) {
                $nextfrom=$matches[1];
            }
            $this->set(compact('nextfrom'));
        }
        if (isset($subscriptions['_links']['previous'])) {
            $href= $subscriptions['_links']['previous']['href'];
            if (preg_match('/from=(\w+)/', $href, $matches)) {
                $prevfrom=$matches[1];
            }
            $this->set(compact('prevfrom'));
        }
        $nbsubscriptions = count($subscriptions);
        $this->set(compact('nbsubscriptions'));
        $this->set(compact('list_customers'));
        $this->set(compact('subscriptions'));
        /*$payments = $mollie->list_payments();
        //var_dump($payments['_embedded']['payments']);
        $list_payments = $payments['_embedded']['payments'];
        //var_dump($list_payments);
        //$list_payments = $this->Paginator->paginate($payments['_embedded']['payments']);
        $this->set(compact('list_payments'));*/
    }

    public function add()
    {
        $mollie = $this->loadModel('Mollie');
        $customers = $mollie->get_customers();
        $this->set(compact('customers'));
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            //var_dump($data);
            $customers = $mollie->get_customer($data['client_id']);
            $customerid = $customers[0]['id'];        
            $listmandates = $mollie->get_mandates($customerid);
            $mandateid=$listmandates[0]['id'];
            //echo $mandateid;
            $now = FrozenTime::now();
            $startdate = $now->i18nFormat('yyyy-MM-dd');
            $amount = strval(number_format(floatval($data['amount']),2));
            if ($data['interval'] == "monthly") {
                $infos = $mollie->create_subscription_monthly($amount, $customerid, $mandateid, $data['description'], $startdate);
            }
            if ($data['interval'] == "annually") {
                $infos = create_subscription_annually($_POST['amount'], $customerid, $mandateid, $_POST['description']);
            }
        }
    }

    public function view($customerid, $subscriptionid)
    {
        $mollie = $this->loadModel('Mollie');
        $subscription = $mollie->get_subscription($customerid, $subscriptionid);
        $this->set(compact('subscription'));
        $list_customers = array();
        $customers = $mollie->get_customers();
        foreach ($customers as $customer) {
            $list_customers[$customer['id']] = $customer;
        }
        $this->set(compact('list_customers'));
    }

    public function delete()
    {
        $subscription_id = $this->request->getQuery('subscription_id');
        $customer_id = $this->request->getQuery('customer_id');
        $mollie = $this->loadModel('Mollie');
        $mollie->cancel_subscription($customer_id, $subscription_id);
        return $this->redirect('/subscriptions/index');
    }
}