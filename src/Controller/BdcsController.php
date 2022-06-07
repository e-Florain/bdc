<?php
// src/Controller/BdcsController.php

namespace App\Controller;
use Cake\Filesystem\Folder;
use Cake\Filesystem\File;
use Cake\I18n\FrozenTime;
use Cake\Http\Client;

class BdcsController extends AppController
{
    
    private $list_keys = array(
        "id" => "Id",
        "name" => "Nom",
        "address" => "Adresse",
        "postcode" => "Code postal",
        "city" => "Ville"
    );

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

    public function index($trasharg="trash:false")
    {
        $this->loadComponent('Paginator');
        $order = $this->request->getQuery('orderby') ?? "name";
        //$sort = isset($this->request->getQuery('sort')) ? $this->request->getQuery('sort') : "ASC";
        $sort = $this->request->getQuery('sort') ?? "ASC";
        $nbitems_trashed = $this->Bdcs->find()->where(['deleted' => 1])->all()->count();
        $nbitems = $this->Bdcs->find()->where(['deleted' => 0])->all()->count();
        if ($trasharg == "trash:true") {
            $this->set('trash_view', true);
            $bdcs = $this->Paginator->paginate($this->Bdcs->find()->where(['deleted' => 1])->order([$order => $sort]));
        } else {
            $this->set('trash_view', false);
            $bdcs = $this->Paginator->paginate($this->Bdcs->find()->where(['deleted' => 0])->order([$order => $sort]));
        }
        $this->set('nbitems_trashed', $nbitems_trashed);
        $this->set('nbitems', $nbitems);
        $this->set(compact('bdcs'));
    }

    public function indexAjax($trasharg="trash:false", $strarg="")
    {
        $str = explode(":", $strarg);
        if (!isset($str[1])) {
            $str[1] = "";
        }
        $this->set('trash_view', "false");
        if ($trasharg == "trash:true") {
            $this->set('trash_view', "true");
            $filters = ['AND' => ['deleted' => 1]];
        }
        else {
            $this->set('trash_view', "false");
            $filters = ['AND' => ['deleted' => 0]];
        }
        //echo $str[1];
        $filters_str = ['OR' => [[' name LIKE' => '%'.$str[1].'%']]];
        $filters_and['AND'][] = $filters_str;
        $this->loadComponent('Paginator');
        $order = $this->request->getQuery('orderby') ?? "name";
        
        $filters['AND'][] = $filters_str;
        //var_dump($filters);
        $sort = $this->request->getQuery('sort') ?? "ASC";
        $query = $this->Bdcs->find()->where($filters)->order([$order => $sort]);
        //$query = $this->Bdcs->find()->where($filters);
        //var_dump($query);
        $nbitems = $this->Bdcs->find()->where($filters)->order([$order => $sort])->count();
        $this->set('nbitems', $nbitems);
        $bdcs = $this->Paginator->paginate($query);
        $this->set(compact('bdcs'));
        $this->viewBuilder()->setLayout('ajax');
    }

    public function add()
    {
        if ($this->request->is('post')) {            
            $bdc = $this->Bdcs->newEmptyEntity();
            $data = $this->request->getData();
            //var_dump($data);
            $this->set(compact('data'));
            $bureau = $this->Bdcs->patchEntity($bdc, $data);
            if ($this->Bdcs->save($bureau)) {
                $this->Flash->success(__('Le bureau a été ajouté.'));
                return $this->redirect(['action' => 'add']);
            } else {
                $errors = $bureau->getErrors();
                if (isset($errors["status"])) {
                    $err = array_values($errors["status"])[0];
                    if (isset($err)) {
                        $this->Flash->error(__('Erreur : '.$err));
                    } else {
                        $this->Flash->error(__('Erreur : Impossible d\'ajouter le bureau.'));
                    } 
                } else {
                    $this->Flash->error(__('Erreur : Impossible d\'ajouter le bureau.'));
                }
                //return $this->redirect('/adhs/index');
            }
        }
    }

    /*public function view($id)
    {
        $bureau = $this->Bdcs->get($id);
        $this->set('list_keys', $this->list_keys);
        $this->set(compact('bureau'));
    }*/

    public function edit($id)
    {
        $bdc = $this->Bdcs->get($id);
        $this->set(compact('bdc'));
        //$this->set('list_payment_type', $this->list_payment_type);
        //var_dump($adhpro);
        if ($this->request->is('post')) {
            //var_dump($this->request->getData());
            //$adhpro = $this->Adhpros->newEmptyEntity();
            $data = $this->request->getData();
            $bdc = $this->Bdcs->patchEntity($bdc, $data);
            if ($this->Bdcs->save($bdc)) {
                $this->Flash->success(__('Le bureau a été modifié.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('Erreur : Impossible de modifier le bureau.'));
                return $this->redirect('/bdcs/index');
            }
        }
    }

    public function delete($id) {
        $bdc = $this->Bdcs->get($id);
        if ($bdc['deleted'] == 1) {
            $result = $this->Bdcs->delete($bdc);
            $this->Flash->success(__('Le bureau a été effacé.'));
            return $this->redirect('/bdcs/index/trash:true');
        } else {
            $bdc['deleted'] = 1;
            if ($this->Bdcs->save($bdc)) {
                $this->Flash->success(__('Le bureau a été effacé.'));
                return $this->redirect('/bdcs/index');
            } else {
                $this->Flash->error(__('Erreur : Impossible d\'effacer Le bureau.'));
                return $this->redirect('/bdcs/index');
            }
        }
    }

    public function restore($id)
    {
        $bdc = $this->Bdcs->get($id);
        if ($bdc['deleted'] == 1) {
            $bdc['deleted'] = 0;
            if ($this->Bdcs->save($bdc)) {
                $this->Flash->success(__('Le bureau a été restauré.'));
                return $this->redirect('/bdcs/index');
            }
        }
    }

    public function syncBdcs() {
        $bdcs = $this->Bdcs->find()->where(['deleted' => 0])->all();
        $adhs = $this->loadModel('Adhesions');
        $adhpros = $adhs->getBdcs();
        $datas = array();
        foreach ($adhpros as $adhpro) {
            $found = false;
            foreach ($bdcs as $bdc) {
                if ($bdc->name == $adhpro['name']) {
                    $found = true;
                }
            }
            if (!$found) {
                $newbdc = $this->Bdcs->newEmptyEntity();
                $data = array();
                $data['name'] = $adhpro['name'];
                $data['address'] = $adhpro['street'];
                $data['city'] = $adhpro['city'];
                $data['postcode'] = $adhpro['zip'];
                $data['phonenumber'] = $adhpro['phone'];
                $newbdc = $this->Bdcs->patchEntity($newbdc, $data);
                $this->Bdcs->save($newbdc);
            }
        }
        $this->Flash->success(__('Synchronisation des bureaux'));
        return $this->redirect('/bdcs/index');
    }
}
?>