<?php
// src/Controller/PaymentsController.php

namespace App\Controller;
use Cake\Filesystem\Folder;
use Cake\Filesystem\File;
use Cake\I18n\FrozenTime;
use Cake\Http\Client;

class PaymentsController extends AppController
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
        $mollie = $this->loadModel('Mollie');
        $payments = $mollie->list_payments($from);
        $list_payments = $payments['_embedded']['payments'];
        if (isset($payments['_links']['next'])) {
            $href= $payments['_links']['next']['href'];
            if (preg_match('/from=(\w+)/', $href, $matches)) {
                $nextfrom=$matches[1];
            }
            $this->set(compact('nextfrom'));
        }
        if (isset($payments['_links']['previous'])) {
            $href= $payments['_links']['previous']['href'];
            if (preg_match('/from=(\w+)/', $href, $matches)) {
                $prevfrom=$matches[1];
            }
            $this->set(compact('prevfrom'));
        }
        $nbpayments = count($list_payments);
        $this->set(compact('nbpayments'));
        $this->set(compact('list_payments'));
    }

    public function onepercent()
    {
        $mollie = $this->loadModel('Mollie');
        $adh = $this->loadModel('Adhesions');
        //$res = $mollie->onepercent();
        $listpaymentsbyassos = $mollie->onepercent();
        $assos = $adh->getOdooAssos();
        $listassos = array();
        foreach ($assos as $asso) {
            $listassos[$asso['id']] = $asso['name'];
        }
        $this->set(compact('listpaymentsbyassos'));
        $this->set(compact('listassos'));
    }
}