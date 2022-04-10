<?php
// src/Controller/AdhsController.php

namespace App\Controller;
use Cake\Filesystem\Folder;
use Cake\Filesystem\File;
use Cake\I18n\FrozenTime;

class AdhsController extends AppController
{
    private $list_payment_type = array("Florains", "Chèques", "Espèces", "HelloAsso");
    private $list_keys = array(
        "adh_id" => "Id",
        "adh_years" => "Année.s d'adh",
        "date_adh" => "Date d'adh",
        "lastname" => "Nom",
        "firstname" => "Prénom",
        "email" => "Email",
        "city" => "Ville",
        "phonenumber" => "Tel",
        "asso_id" => "Asso",
        "amount" => "Montant",
        "payment_type" => "Paiement",
        "cyclos_account" => "Cyclos",
        "newsletter" => "NL"
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
                $this->Auth->allow(['index', 'indexAjax']);
            }
        }
    }

    /*public function isAuthorized($user)
    {
        echo "test";
        return true;
    }*/

    public function index($trasharg="trash:false")
    {
        $this->loadComponent('Paginator');
        $this->loadModel('Associations');
        $order = $this->request->getQuery('orderby') ?? "adh_id";
        //$sort = isset($this->request->getQuery('sort')) ? $this->request->getQuery('sort') : "ASC";
        $sort = $this->request->getQuery('sort') ?? "ASC";
        //var_dump($sort);
        $nbitems_trashed = $this->Adhs->find()->where(['deleted' => 1])->all()->count();
        $nbitems = $this->Adhs->find()->where(['deleted' => 0])->all()->count();
        if ($trasharg == "trash:true") {
            $this->set('trash_view', true);
            $adhs = $this->Paginator->paginate($this->Adhs->find()->where(['deleted' => 1])->order([$order => $sort]));
        } else {
            $this->set('trash_view', false);
            $adhs = $this->Paginator->paginate($this->Adhs->find()->where(['deleted' => 0])->order([$order => $sort]));
        }
        $this->set('nbitems_trashed', $nbitems_trashed);
        $this->set('nbitems', $nbitems);
        $this->set(compact('adhs'));
        $assos = $this->Associations->find()->where(['deleted' => 0]);
        $this->set('assos', $assos);
        
        //$this->Flash->success(__('The adh has been saved.'));
       // echo "true ".$order;
    }

    public function indexAjax($trasharg="trash:false", $strarg="", $yearsarg="")
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
        $filters_str = ['OR' => [['firstname LIKE' => '%'.$str[1].'%'], ['lastname LIKE' => '%'.$str[1].'%'], ['email LIKE' => '%'.$str[1].'%']]];
        $yearstr = explode(":", $yearsarg);
        if (!isset($yearstr[1])) {
            $yearstr[1] = "";
        } else {
            $filters_years = array();
            $list_years_adh = explode(";", $yearstr[1]);
            foreach ($list_years_adh as $year) {
                if ($year != "") {
                    $filters_years['OR'][] = ['adh_years LIKE' => '%'.$year.'%'];
                }
            }
        }
        $filters_and['AND'][] = $filters_str;
        $this->loadComponent('Paginator');
        $this->loadModel('Associations');
        $order = $this->request->getQuery('orderby') ?? "adh_id";
        
        $filters['AND'][] = $filters_years;
        $filters['AND'][] = $filters_str;
        //var_dump($filters);
        $sort = $this->request->getQuery('sort') ?? "ASC";
        $query = $this->Adhs->find()->where($filters)->order([$order => $sort]);
        $nbitems = $this->Adhs->find()->where($filters)->order([$order => $sort])->count();
        $this->set('nbitems', $nbitems);
        $adhs = $this->Paginator->paginate($query);
        $this->set(compact('adhs'));
        $assos = $this->Associations->find()->where(['deleted' => 0]);
        $this->set('assos', $assos);
        $this->viewBuilder()->setLayout('ajax');
    }

    public function add()
    {
        $this->loadModel('Associations');
        $assos = $this->Associations->find()->where(['deleted' => 0])->order(['asso_id' => 'ASC']);
        $this->set('assos', $assos);
        $this->set('list_payment_type', $this->list_payment_type);
        if ($this->request->is('post')) {            
            //$adh = $this->Adhs->newEmptyEntity();
            $data = $this->request->getData();
            if (isset($data["newsletter"])) {
                if ($data["newsletter"] == "on") {
                    $data["newsletter"] = True;
                } else {
                    $data["newsletter"] = False;
                }
            } else {
                $data["newsletter"] = False;
            }
            if (isset($data["cyclos_account"])) {
                if ($data["cyclos_account"] == "on") {
                    $data["cyclos_account"] = True;
                } else {
                    $data["cyclos_account"] = False;
                }
            } else {
                $data["cyclos_account"] = False;
            }
            $tmp = "";
            if (isset($data["adh_years"])) {
                foreach ($data["adh_years"] as $adh_year) {
                    $tmp=$tmp.$adh_year.";";
                }
            }
            $data["adh_years"] = $tmp;
            $data["date_adh"] = $data["date_adh"]."00:00:00";
            $adh = $this->Adhs->newEntity($data);
            if ($adh->getErrors()) {
                var_dump($adh->getErrors());
            } else {
                $adh = $this->Adhs->patchEntity($adh, $data);
                if ($this->Adhs->save($adh)) {
                    $this->Flash->success(__('L\'adhérent a été ajouté.'));
                    return $this->redirect(['action' => 'add']);
                } else {
                    $errors = $adh->getErrors();
                    if (isset($errors["status"])) {
                        $err = array_values($errors["status"])[0];
                        if (isset($err)) {
                            $this->Flash->error(__('Erreur : '.$err));
                        } else {
                            $this->Flash->error(__('Erreur : Impossible d\'ajouter l\'adhérent.'));
                        } 
                    } else {
                        $this->Flash->error(__('Erreur : Impossible d\'ajouter l\'adhérent.'));
                    }
                    //return $this->redirect('/adhs/index');
                }
            }
        }
    }

    public function edit($id)
    {
        $this->loadModel('Associations');
        $assos = $this->Associations->find()->where(['deleted' => 0])->order(['asso_id' => 'ASC']);
        $this->set('assos', $assos);
        $adh = $this->Adhs->get($id);
        //var_dump($adh);
        $this->set(compact('adh'));
        $this->set('list_payment_type', $this->list_payment_type);
        if ($this->request->is('post')) {
            //var_dump($this->request->getData());
            //$adh = $this->Adhs->newEmptyEntity();
            $data = $this->request->getData();
            //var_dump($data);
            if (isset($data["newsletter"])) {
                if ($data["newsletter"] == "on") {
                    $data["newsletter"] = True;
                } else {
                    $data["newsletter"] = False;
                }
            } else {
                $data["newsletter"] = False;
            }
            if (isset($data["cyclos_account"])) {
                if ($data["cyclos_account"] == "on") {
                    $data["cyclos_account"] = True;
                } else {
                    $data["cyclos_account"] = False;
                }
            } else {
                $data["cyclos_account"] = False;
            }
            $tmp = "";
            if (isset($data["adh_years"])) {
                foreach ($data["adh_years"] as $adh_year) {
                    $tmp=$tmp.$adh_year.";";
                }
            }
            $data["adh_years"] = $tmp;
            $data["date_adh"] = $data["date_adh"]."00:00:00";
            $adh = $this->Adhs->patchEntity($adh, $data);
            if ($this->Adhs->save($adh)) {
                $this->Flash->success(__('L\'adhérent a été modifié.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('Erreur : Impossible de modifier l\'adhérent.'));
                return $this->redirect('/adhs/index');
            }
        }
    }

    public function applyYears($id, $years)
    {
        $adh = $this->Adhs->get($id);
        $yeararr1 = explode(";", $adh["adh_years"]);
        $yeararr2 = explode(";", $years);
        $tmp = "";
        $arr = array_filter(array_unique(array_merge($yeararr1, $yeararr2)));
        foreach ($arr as $adh_year) {
            $tmp=$tmp.$adh_year.";";
        }
        $adh["adh_years"] = $tmp;
        if ($this->Adhs->save($adh)) {
            $this->Flash->success(__('L\'adhérent a été modifié.'));
            //return $this->redirect('/adhs/index');
        }
        //$this->viewBuilder()->setLayout('ajax');
    }

    public function delete($id)
    {
        $adh = $this->Adhs->get($id);
        if ($adh['deleted'] == 1) {
            $result = $this->Adhs->delete($adh);
            $this->Flash->success(__('L\'adhérent a été effacé.'));
            return $this->redirect('/adhs/index/trash:true');
        } else {
            $adh['deleted'] = 1;
            if ($this->Adhs->save($adh)) {
                $this->Flash->success(__('L\'adhérent a été effacé.'));
                return $this->redirect('/adhs/index');
            } else {
                $this->Flash->error(__('Erreur : Impossible d\'effacer l\'adhérent.'));
                return $this->redirect('/adhs/index');
            }
        }
    }

    public function restore($id)
    {
        $adh = $this->Adhs->get($id);
        if ($adh['deleted'] == 1) {
            $adh['deleted'] = 0;
            if ($this->Adhs->save($adh)) {
                $this->Flash->success(__('L\'adhérent a été restauré.'));
                return $this->redirect('/adhs/index');
            }
        }
    }

    public function importexport()
    {

    }

    public function import()
    {
        $uploadDatas = array();
        if ($this->request->is('post')) {
            $data = $this->request->getData()["uploadfile"];
            
            if(!empty($data)){
                $this->loadModel('Associations');
                $assos = $this->Associations->find();
                $datas = $assos->toArray();
                $assos = array_column($datas, 'id', 'asso_id');
                $fileName = $data->getClientFilename();
                //var_dump($fileName);
                $stream = $data->getStream();
                $infos = explode("\n", $stream);
                $data = array();
                $i=0;
                $keys = str_getcsv($infos[0]);
                //var_dump($keys);
                for ($i=1;$i<count($infos);$i++) {
                    $datacsv = str_getcsv($infos[$i]);
                    //var_dump($datacsv);
                    //echo "<br>";
                    //echo count($keys)." ".count($datacsv);
                    if (count($keys) == count($datacsv)) {
                        $data = array_combine($keys, $datacsv); 
                        if ($data != FALSE) {
                            $adh = $this->Adhs->newEmptyEntity();
                            $data["date_adh"] = $data["date_adh"]."00:00:00";
                            $data["asso_id"] = $assos[$data["asso_id"]];
                            $adh = $this->Adhs->patchEntity($adh, $data);
                            if ($this->Adhs->save($adh)) {
                                $data['imported'] = 1;
                                $data['msgerr'] = '';
                            } else {
                                $errors = $adh->getErrors()["status"];
                                $data['msgerr'] = array_shift($errors);
                                $data['imported'] = 0;
                            }
                            $uploadDatas[] = $data;
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
        $this->loadModel('Associations');
        $assos = $this->Associations->find();
        $datas = $assos->toArray();
        $assos = array_column($datas, 'asso_id', 'id');
        //var_dump($assos);
        
        $now = FrozenTime::now();
        $strfile = $now->format('Y-m-d').'_export_particuliers.csv';
        $users = $this->Adhs->find()->where(['deleted' => 0])->all();
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
        foreach ($users as $user) {
            if ($user->date_adh != null) {
                $datestr = $user->date_adh->format('Y-m-d');
            } else {
                $datestr="";
            }
            $infos=$user->adh_id.",".$user->adh_years.",".$datestr.",".$user->lastname.",".$user->firstname.",".$user->email.",".$user->city.",".$user->phonenumber.",".$assos[$user->asso_id].",".$user->amount.",".$user->payment_type.",".$user->cyclos_account.",".$user->newsletter."\n";
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
}
?>