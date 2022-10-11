<?php
// src/Controller/TransactionsController.php

namespace App\Controller;
use Cake\Filesystem\Folder;
use Cake\Filesystem\File;
use Cake\I18n\FrozenTime;
use Cake\Http\Client;

class TransactionsController extends AppController
{

    private $list_keys = array(
        "date" => "Date",
        "adh_id" => "Numéro d'adhérent",
        "adh_name" => "Nom d'adhérent",
        "amount" => "Montant",
        "payment_type" => "Type de paiement"
    );

    private $list_payment_type = array("Chèques", "Espèces");

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
        $this->loadModel('Cashdesks');
        $cashdesk_id = $this->request->getQuery('cashdesk_id');
        //echo $cashdesk_id;
        $cashdesk = $this->Cashdesks->find()->where(['deleted' => 0, 'id' => $cashdesk_id])->first();
        //var_dump($cashdesk->description);
        $this->set('cashdesk', $cashdesk);

        $this->loadModel('Bdcs');
        $this->set('bdc_name', $this->Bdcs->getName($cashdesk->bdc_id));
        //echo $this->Bdcs->getName(1);
        //$cashdesks = $this->Cashdesks->find()->where(['deleted' => 0])->order(['id' => 'ASC']);
        
        $this->loadComponent('Paginator');
        $order = $this->request->getQuery('orderby') ?? "date";
        //$sort = isset($this->request->getQuery('sort')) ? $this->request->getQuery('sort') : "ASC";
        $sort = $this->request->getQuery('sort') ?? "ASC";
        $nbitems_trashed = $this->Transactions->find()->where(['deleted' => 1, 'cashdesk_id' => $cashdesk_id])->all()->count();
        $nbitems = $this->Transactions->find()->where(['deleted' => 0, 'cashdesk_id' => $cashdesk_id])->all()->count();
        if ($trasharg == "trash:true") {
            $this->set('trash_view', true);
            $transactions = $this->Paginator->paginate($this->Transactions->find()->where(['deleted' => 1, 'cashdesk_id' => $cashdesk_id])->order([$order => $sort]));
        } else {
            $this->set('trash_view', false);
            $transactions = $this->Paginator->paginate($this->Transactions->find()->where(['deleted' => 0, 'cashdesk_id' => $cashdesk_id])->order([$order => $sort]));
        }
        $this->set('nbitems_trashed', $nbitems_trashed);
        $this->set('nbitems', $nbitems);
        $this->set('cashdesk_id', $cashdesk_id);
        $this->set(compact('transactions'));
        $this->set('list_keys', $this->list_keys);
        //$this->getAdhsById();
    }
    /*
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
        $query = $this->Transactions->find()->where($filters)->order([$order => $sort]);
        //$query = $this->Transactions->find()->where($filters);
        //var_dump($query);
        $nbitems = $this->Transactions->find()->where($filters)->order([$order => $sort])->count();
        $this->set('nbitems', $nbitems);
        $Transactions = $this->Paginator->paginate($query);
        $this->set(compact('Transactions'));
        $this->viewBuilder()->setLayout('ajax');
    }*/

    public function add()
    {
        $this->loadModel('Cashdesks');
        $cashdesk_id = $this->request->getQuery('cashdesk_id');
        $cashdesk = $this->Cashdesks->find()->where(['deleted' => 0, 'id' => $cashdesk_id])->first();
        $this->set('date', $cashdesk->date->format('Y-m-d H:i:s'));
        $this->set('cashdesk_id', $cashdesk_id);
        $this->set('list_payment_type', $this->list_payment_type);
        if ($cashdesk->closed) {
            $this->Flash->success(__('Impossible : la caisse est fermée'));
            return $this->redirect(['controller' => 'cashdesks', 'action' => 'index']);
        }
        if ($this->request->is('post')) {            
            $data = $this->request->getData();
            if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$data["date"])) {
                $data["date"] = $data["date"]."00:00:00";
            }
            //$data["date"] = $data["date"]."00:00:00";
            $data["adh_name"] = $data["adh_lastname"]." ".$data["adh_firstname"];
            $transaction = $this->Transactions->newEntity($data);
            if ($this->Transactions->save($transaction)) {
                $this->Flash->success(__('La transaction a été ajoutée.'));
                return $this->redirect(['action' => 'add?cashdesk_id='.$cashdesk_id]);
            } else {
                $errors = $transaction->getErrors();
                if (isset($errors["adh"])) {
                    $err = array_values($errors["adh"])[0];
                    if (isset($err)) {
                        $this->Flash->error(__('Erreur : '.$err));
                    } else {
                        $this->Flash->error(__('Erreur : Impossible d\'ajouter la transaction.'));
                    } 
                }
                elseif (isset($errors["status"])) {
                    $err = array_values($errors["status"])[0];
                    if (isset($err)) {
                        $this->Flash->error(__('Erreur : '.$err));
                    } else {
                        $this->Flash->error(__('Erreur : Impossible d\'ajouter la transaction.'));
                    } 
                } else {
                    $this->Flash->error(__('Erreur : Impossible d\'ajouter la transaction.'));
                }
                //return $this->redirect('/adhs/index');
            }
        }
    }

    /*public function view($id)
    {
        $transaction = $this->Transactions->get($id);
        $this->set('list_keys', $this->list_keys);
        $this->set(compact('transaction'));
    }*/

    public function edit($id)
    {
        $transaction = $this->Transactions->get($id);
        $this->set(compact('transaction'));
        //$this->set('list_payment_type', $this->list_payment_type);
        //var_dump($adhpro);
        if ($this->request->is('post')) {
            //var_dump($this->request->getData());
            //$adhpro = $this->Adhpros->newEmptyEntity();
            $data = $this->request->getData();
            $transaction = $this->Transactions->patchEntity($transaction, $data);
            if ($this->Transactions->save($transaction)) {
                $this->Flash->success(__('La transaction a été modifiée.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('Erreur : Impossible de modifier le transaction.'));
                return $this->redirect('/Transactions/index');
            }
        }
    }

    public function delete($id) {
        $transaction = $this->Transactions->get($id);
        if ($transaction['deleted'] == 1) {
            $result = $this->Transactions->delete($transaction);
            $this->Flash->success(__('La transaction a été effacée.'));
            return $this->redirect('/Transactions/index/trash:true?cashdesk_id='.$transaction->cashdesk_id);
        } else {
            $transaction['deleted'] = 1;
            if ($this->Transactions->save($transaction)) {
                $this->Flash->success(__('La transaction a été effacée.'));
                return $this->redirect('/Transactions/index?cashdesk_id='.$transaction->cashdesk_id);
            } else {
                $this->Flash->error(__('Erreur : Impossible d\'effacer la transaction.'));
                return $this->redirect('/Transactions/index?cashdesk_id='.$transaction->cashdesk_id);
            }
        }
    }

    public function restore($id)
    {
        $transaction = $this->Transactions->get($id);
        if ($transaction['deleted'] == 1) {
            $transaction['deleted'] = 0;
            if ($this->Transactions->save($transaction)) {
                $this->Flash->success(__('La transaction a été restaurée.'));
                return $this->redirect('/Transactions/index?cashdesk_id='.$transaction->cashdesk_id);
            }
        }
    }

    public function importexport()
    {
        $cashdesk_id = $this->request->getQuery('cashdesk_id');
        $this->set('cashdesk_id', $cashdesk_id);
    }

    public function import($cashdesk_id)
    {
        $this->set('cashdesk_id', $cashdesk_id);
        $uploadDatas = array();
        if ($this->request->is('post')) {
            $data = $this->request->getData()["uploadfile"];
            if(!empty($data)){
                $fileName = $data->getClientFilename();
                $stream = $data->getStream();
                $infos = explode("\n", $stream);
                $data = array();
                $i=0;
                $keys = str_getcsv($infos[0]);
                for ($i=1;$i<count($infos);$i++) {
                    $datacsv = str_getcsv($infos[$i]);
                    if (count($keys) == count($datacsv)) {
                        $data = array_combine($keys, $datacsv); 
                        if ($data != FALSE) {
                            $transaction = $this->Transactions->newEmptyEntity();
                            $data["date"] = $data["date"]."00:00:00";
                            $data["cashdesk_id"] = $cashdesk_id;
                            $transaction = $this->Transactions->patchEntity($transaction, $data);
                            if ($this->Transactions->save($transaction)) {
                                $data['imported'] = 1;
                                $data['msgerr'] = '';
                            } else {
                                $errors = $transaction->getErrors()["status"];
                                $data['msgerr'] = array_shift($errors);
                                $data['imported'] = 0;
                            }
                            $uploadDatas[] = $data;
                            //break;
                        }
                    }
                }

                $this->set('list_keys', $this->list_keys);
                $this->set(compact('uploadDatas'));
            }else{
                $this->Flash->error(__('Unable to upload file, please try again.'));
            }
        }
    }

    public function export()
    {
        $users = $this->Adhpros->find();
        $now = FrozenTime::now();
        $strfile = $now->format('Y-m-d').'_export_pros.csv';
        $file = new File($strfile, true, 0644);
        $exportCSV="";
        $i=0;
        foreach($this->list_keys as $key=>$keyname) {
            if ($i==(count($this->list_keys)-1)) {
                $exportCSV=$exportCSV.$key;
            } else {
                $exportCSV=$exportCSV.$key.",";
            }
            $i++;
        }
        $exportCSV=$exportCSV."\n";
        $file->write($exportCSV);
        //$file->write("adh_id,date_adh,orga_name,orga_contact,email,address,postcode,city,phonenumber,asso,amount,payment_type,invoice,newsletter,annuaire\n");
        //$exportCSV=$exportCSV."adh_id,date_adh,orga_name,orga_contact,email,address,postcode,city,phonenumber,asso,amount,payment_type,invoice,newsletter,annuaire\n";
        foreach ($users as $user) {
            if ($user->date_adh != null) {
                $datestr = $user->date_adh->format('Y-m-d');
            } else {
                $datestr="";
            }
            $infos=$user->adh_id.",".$user->adh_years.",".$datestr.",".$user->orga_name.",".$user->orga_contact.",".$user->email.",".$user->address.",".$user->postcode.",".$user->city.",".$user->phonenumber.",".$user->amount.",".$user->payment_type.",".$user->cyclos_account.",".$user->invoice.",".$user->newsletter.",".$user->annuaire."\n";
            $exportCSV=$exportCSV.$infos;
            $file->append($infos);
        }
        $path = $file->path;
        $file->close();
        
        $response = $this->response->withFile(
            $path = $file->path,
            ['download' => true, 'name' => $strfile]
        );
        return $response;
    }

    public function getAdhsById($id="") {
        $adhs = $this->loadModel('Adhesions');
        $results = $adhs->getAdhs();
        $res = array();
        if ($id != "") {
            foreach ($results as $result) {
                if ($result["ref"] == $id) {
                    $found = true;
                    $res["lastname"]=$result["lastname"];
                    $res["firstname"]=$result["firstname"];
                    $res["ref"]=$result["ref"];
                    $res["state"]=$result["membership_state"];
                }
            }
        }
        $json = json_encode($res);
        echo $json;
    }

    public function getAdhsByName() {
        $lastname = $this->request->getQuery('lastname');
        $firstname = $this->request->getQuery('firstname');
        $params = array(
            "firstname" => $firstname,
            "lastname" => $lastname
        );
        $adhs = $this->loadModel('Adhesions');
        $results = $adhs->getAdhs($params);
        $arr = array();
        foreach ($results as $result) {
            $arr[$result["lastname"]] = array(
                "lastname" => $result["lastname"],
                "firstname" => $result["firstname"],
                "ref" => $result["ref"], 
                "state" => $result["membership_state"]);
        }
        $json = json_encode($arr);
        echo $json;
    }
}
?>