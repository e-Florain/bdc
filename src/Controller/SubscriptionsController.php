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
        $now = FrozenTime::now();
        $startdate = $now->i18nFormat('yyyy-MM-dd');
        $this->set(compact('startdate'));
        $this->set(compact('customers'));
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            //var_dump($data);
            $customers = $mollie->get_customer($data['client_id']);
            $customerid = $customers[0]['id'];        
            $listmandates = $mollie->get_mandates($customerid);
            if (count($listmandates) == 0) {
                $this->Flash->error(__('Aucun mandata n\'a été trouvé.'));
            } else {
                $mandateid=$listmandates[0]['id'];            
                $amount = strval(number_format(floatval($data['amount']),2));
                if ($data['interval'] == "monthly") {
                    $infos = $mollie->create_subscription_monthly($amount, $customerid, $mandateid, $data['description'], $data['startdate'], $data['times']);
                    $this->Flash->success(__('Le prélèvement a été créé.'));
                }
                if ($data['interval'] == "annually") {
                    $infos = $mollie->create_subscription_annually($amount, $customerid, $mandateid, $data['description'], $data['startdate'], $data['times']);
                    $this->Flash->success(__('Le prélèvement a été créé.'));
                }
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

    public function edit($customerid, $subscriptionid)
    {
        $now = FrozenTime::now();
        $startdate = $now->i18nFormat('yyyy-MM-dd');
        $this->set(compact('startdate'));
        $mollie = $this->loadModel('Mollie');
        $subscription = $mollie->get_subscription($customerid, $subscriptionid);
        $this->set(compact('subscription'));
        $list_customers = array();
        $customers = $mollie->get_customers();
        foreach ($customers as $customer) {
            $list_customers[$customer['id']] = $customer;
        }
        $this->set(compact('list_customers'));
        $this->set(compact('customers'));
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $amount = strval(number_format(floatval($data['amount']),2));
            $infos = $mollie->update_subscription($subscriptionid, $customerid, $amount, $data['times']);
            $this->Flash->success(__('Le prélèvement a été modifié.'));
            return $this->redirect('/subscriptions/index');
        }
    }

    public function delete()
    {
        $subscription_id = $this->request->getQuery('subscription_id');
        $customer_id = $this->request->getQuery('customer_id');
        $mollie = $this->loadModel('Mollie');
        $mollie->cancel_subscription($customer_id, $subscription_id);
        return $this->redirect('/subscriptions/index');
    }

    public function scriptChangetoOdoo()
    {
        $mollie = $this->loadModel('Mollie');
        $adhesions = $this->loadModel('Adhesions');
        $list_customers = array();
        $customers = $mollie->get_customers();
        //var_dump($customers);
        foreach ($customers as $customer) {
            $list_customers[$customer['id']] = $customer;
        }
        $list_subscriptions = array();
        $subscriptions = $mollie->get_all_subscriptions();
        foreach ($subscriptions as $subscription) {
            $newsubscription = array();
            if (preg_match('/Change/',$subscription['description'])) {
                if (isset($list_customers[$subscription['customerId']])) {
                    $email = $list_customers[$subscription['customerId']]['email'];
                    $newsubscription['customerEmail'] = $email;
                    $newsubscription['amount'] = $subscription['amount']['value'];
                    $list_subscriptions[] = $newsubscription;
                }               
            }
        }
        /*foreach ($list_subscriptions as $subscription) {
            $datas = array(
                'email' => $subscription['customerEmail'],
                'infos' => array(
                    'changeeuros' => $subscription['amount']
                )
            );
            echo $adhesions->updateAdh($datas);
        }
        echo "<pre>";
        var_dump($list_subscriptions);
        echo "</pre>";*/
    }

}