<?php
// src/Controller/CashdesksController.php

namespace App\Controller;
use Cake\Filesystem\Folder;
use Cake\Filesystem\File;
use Cake\I18n\FrozenTime;

class CashdesksController extends AppController
{
    private $list_keys = array(
        "id" => "Id",
        "name" => "Nom",
        "date" => "Date"
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
                $this->Auth->allow(['index', 'add']);
            }
        }
    }

    public function index($trasharg="trash:false")
    {
        $this->loadModel('Bdcs');
        $bdcs = $this->Bdcs->find()->where(['deleted' => 0])->order(['id' => 'ASC']);
        $this->set('bdcs', $bdcs);
        $this->loadComponent('Paginator');
        $order = $this->request->getQuery('orderby') ?? "date";
        //$sort = isset($this->request->getQuery('sort')) ? $this->request->getQuery('sort') : "ASC";
        $sort = $this->request->getQuery('sort') ?? "DESC";
        if ($_SESSION["Auth"]->role != 'root') {
            $nbitems_trashed = $this->Cashdesks->find()->where(['deleted' => 1, 'bdc_id' => $_SESSION["Auth"]->bdc_id])->all()->count();
            $nbitems = $this->Cashdesks->find()->where(['deleted' => 0, 'bdc_id' => $_SESSION["Auth"]->bdc_id])->all()->count();
            if ($trasharg == "trash:true") {
                $this->set('trash_view', true);
                $cashdesks = $this->Paginator->paginate($this->Cashdesks->find()->where(['deleted' => 1, 'bdc_id' => $_SESSION["Auth"]->bdc_id])->order([$order => $sort]));
            } else {
                $this->set('trash_view', false);
                $cashdesks = $this->Paginator->paginate($this->Cashdesks->find()->where(['deleted' => 0, 'bdc_id' => $_SESSION["Auth"]->bdc_id])->order([$order => $sort]));
            }
        } else {
            $nbitems_trashed = $this->Cashdesks->find()->where(['deleted' => 1])->all()->count();
            $nbitems = $this->Cashdesks->find()->where(['deleted' => 0])->all()->count();
            if ($trasharg == "trash:true") {
                $this->set('trash_view', true);
                $cashdesks = $this->Paginator->paginate($this->Cashdesks->find()->where(['deleted' => 1])->order([$order => $sort]));
            } else {
                $this->set('trash_view', false);
                $cashdesks = $this->Paginator->paginate($this->Cashdesks->find()->where(['deleted' => 0])->order([$order => $sort]));
            }
        }
        $this->set(compact('cashdesks'));
        $this->set('nbitems_trashed', $nbitems_trashed);
        $this->set('nbitems', $nbitems);
        $this->set('role', $_SESSION["Auth"]->role);
        //$this->Flash->success(__('The adh has been saved.'));
       // echo "true ".$order;
    }

    public function add()
    {
        if ($this->request->is('post')) {            
            $data = $this->request->getData();
            $data["date"] = $data["date"]."00:00:00";
            $data['bdc_id'] = $_SESSION['Auth']->bdc_id;
            $cashdesk = $this->Cashdesks->newEntity($data);
            //var_dump($cashdesk);
            if ($cashdesk->getErrors()) {
                var_dump($cashdesk->getErrors());
            } else {
                if ($this->Cashdesks->save($cashdesk)) {
                    $this->Flash->success(__('La caisse a été ajoutée.'));
                    return $this->redirect('/cashdesks/index');
                } else {
                    $this->Flash->error(__('Erreur : Impossible d\'ajouter la caisse.'));
                    //return $this->redirect('/cashdesks/index');
                }
            }
        }
    }

    public function addAdmin()
    {
        $this->loadModel('Bdcs');
        $bdcs = $this->Bdcs->find()->where(['deleted' => 0])->order(['id' => 'ASC']);
        $this->set('bdcs', $bdcs);
        if ($this->request->is('post')) {            
            $data = $this->request->getData();
            $data["date"] = $data["date"]."00:00:00";
            $cashdesk = $this->Cashdesks->newEntity($data);
            //var_dump($cashdesk);
            if ($cashdesk->getErrors()) {
                var_dump($cashdesk->getErrors());
            } else {
                if ($this->Cashdesks->save($cashdesk)) {
                    $this->Flash->success(__('La caisse a été ajoutée.'));
                    return $this->redirect('/cashdesks/index');
                } else {
                    $this->Flash->error(__('Erreur : Impossible d\'ajouter la caisse.'));
                    //return $this->redirect('/cashdesks/index');
                }
            }
        }
    }

    public function edit($id)
    {
        $cashdesk = $this->Cashdesks->get($id);
        //var_dump($adh);
        $this->set(compact('cashdesk'));
        if ($this->request->is('post')) {
            //var_dump($this->request->getData());
            $cashdesk = $this->Cashdesks->newEmptyEntity();
            $data = $this->request->getData();
            //var_dump($data);
            $cashdesk = $this->Cashdesks->patchEntity($cashdesk, $data);
            if ($this->Cashdesks->save($cashdesk)) {
                $this->Flash->success(__('La caisse a été modifiée.'));
                return $this->redirect('/cashdesks/index');
            } else {
                $this->Flash->error(__('Erreur : Impossible de modifier La caisse.'));
                return $this->redirect('/cashdesks/index');
            }
        }
    }

    public function delete($id) {
        $cashdesk = $this->Cashdesks->get($id);
        if ($cashdesk['deleted'] == 1) {
            $result = $this->Cashdesks->delete($adhpro);
            $this->Flash->success(__('La caisse a été effacée.'));
            return $this->redirect('/cashdesks/index');
        } else {
            $cashdesk['deleted'] = 1;
            if ($this->Cashdesks->save($cashdesk)) {
                $this->Flash->success(__('La caisse a été effacé.'));
                return $this->redirect('/cashdesks/index');
            } else {
                $this->Flash->error(__('Erreur : Impossible d\'effacer La caisse.'));
                return $this->redirect('/cashdesks/index');
            }
        }
    }

    public function restore($id)
    {
        $cashdesk = $this->Cashdesks->get($id);
        if ($cashdesk['deleted'] == 1) {
            $cashdesk['deleted'] = 0;
            if ($this->Cashdesks->save($cashdesk)) {
                $this->Flash->success(__('La caisse a été restauré.'));
                return $this->redirect('/cashdesks/index');
            }
        }
    }

    public function search()
    {
        $cashdesk = $this->Cashdesks->find()->all();

        $this->set(compact('cashdesks'));
        //$this->viewBuilder()->setOption('serialize', 'cashdesks');
        $this->viewBuilder()->setLayout('ajax');
        return $this->response->withType('application/json')
            ->withStringBody(json_encode($cashdesks));

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
                //var_dump($infos);
                for ($i=1;$i<count($infos);$i++) {
                    $datacsv = str_getcsv($infos[$i]);
                    if (count($keys) == count($datacsv)) {
                        $data = array_combine($keys, $datacsv); 
                        if ($data != FALSE) {
                            $cashdesk = $this->Cashdesks->newEmptyEntity();
                            $cashdesk = $this->Cashdesks->patchEntity($cashdesk, $data);
                            //var_dump($adh);
                            if ($this->Cashdesks->save($cashdesk)) {
                                $data['imported'] = 1;
                                $data['msgerr'] = '';
                            } else {
                                $errors = $cashdesk->getErrors()["status"];
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
        $now = FrozenTime::now();
        $strfile = $now->format('Y-m-d').'_export_cashdesks.csv';
        $cashdesks = $this->Cashdesks->find();
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
        foreach ($cashdesks as $cashdesk) {
            $infos=$cashdesk->id.",".$cashdesk->name.",".$cashdesk->date."\n";
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