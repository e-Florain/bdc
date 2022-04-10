<?php
// src/Controller/BdcsController.php

namespace App\Controller;
use Cake\Filesystem\Folder;
use Cake\Filesystem\File;
use Cake\I18n\FrozenTime;

class BdcsController extends AppController
{

    //private $list_payment_type = array("Florains", "Chèques", "Espèces", "HelloAsso");
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
        //$this->set('list_payment_type', $this->list_payment_type);
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

    public function importexport()
    {

    }

    public function import()
    {
        $uploadDatas = array();
        if ($this->request->is('post')) {
            $data = $this->request->getData()["uploadfile"];
            
            if(!empty($data)){
                $fileName = $data->getClientFilename();
                //var_dump($fileName);
                $stream = $data->getStream();
                $infos = explode("\n", $stream);
                $data = array();
                $i=0;
                $keys = str_getcsv($infos[0]);
                for ($i=1;$i<count($infos);$i++) {
                    $datacsv = str_getcsv($infos[$i]);
                    if (count($keys) == count($datacsv)) {
                        $data = array_combine($keys, $datacsv); 
                        //var_dump($data);
                        if ($data != FALSE) {
                            $adhpro = $this->Adhpros->newEmptyEntity();
                            $data["phonenumber"] = str_replace(".", "", $data["phonenumber"]);
                            $data["phonenumber"] = str_replace(" ", "", $data["phonenumber"]);
                            $data["date_adh"] = $data["date_adh"]."00:00:00";
                            //var_dump($data);
                            $adhpro = $this->Adhpros->patchEntity($adhpro, $data);
                            var_dump($adhpro);
                            if ($this->Adhpros->save($adhpro)) {
                                $data['imported'] = 1;
                                $data['msgerr'] = '';
                            } else {
                                $errors = $adhpro->getErrors()["status"];
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
}
?>