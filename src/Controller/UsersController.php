<?php
// src/Controller/UsersController.php

namespace App\Controller;

class UsersController extends AppController
{
    private $list_roles = array("User", "Admin", "Root");

    public function index()
    {
        $this->loadComponent('Paginator');
        $order = $this->request->getQuery('orderby') ?? "lastname";
        //$sort = isset($this->request->getQuery('sort')) ? $this->request->getQuery('sort') : "ASC";
        $sort = $this->request->getQuery('sort') ?? "ASC";
        //var_dump($sort);
        $users = $this->Paginator->paginate($this->Users->find()->order([$order => $sort]));
        $this->set(compact('users'));
        
        //$this->Flash->success(__('The adh has been saved.'));
       // echo "true ".$order;
    }

    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        parent::beforeFilter($event);
        if (isset($_SESSION["Auth"])) {
            if ($_SESSION["Auth"]->role == 'root') {
                $this->Auth->allow();
            } else {
                $this->Auth->allow(['login', 'logout', 'resetPassword']);
            }
        }
        $this->Authentication->addUnauthenticatedActions(['login']);
    }

    public function login()
    {
        //echo password_hash("1234", PASSWORD_DEFAULT);
        $this->viewBuilder()->setLayout('login');
        $data = $this->request->getData();
        
        if ($this->request->is('post')) {
            $user = $this->findUser($data["email"]);
            if (!is_null($user)) {
                //var_dump($user);
                if (password_verify($data["password"], $user->password)) {
                    $session = $this->request->getSession();
                    $session->write('User.name', $user->firstname." ".$user->lastname);
                    $session->write('User.id', $user->id);
                    if ($user->role == "root") {
                        $redirect = $this->request->getQuery('redirect', [
                            'controller' => 'Bdcs',
                            'action' => 'index',
                        ]);
                    }
                    if ($user->role == "user") {
                        $redirect = $this->request->getQuery('redirect', [
                            'controller' => 'Cashdesks',
                            'action' => 'index',
                        ]);
                    }
                    return $this->redirect($redirect);
                } else {
                    $this->Flash->error(__('Email ou mot passe invalide'));
                }
            } else {
                $this->Flash->error(__('Email ou mot passe invalide'));
            }
        }
    }

    // in src/Controller/UsersController.php
    public function logout()
    {
        $this->Authentication->logout();
        return $this->redirect(['controller' => 'Users', 'action' => 'login']);
    }

    public function add()
    {
        $this->set('list_roles', $this->list_roles);
        $user = $this->Users->newEmptyEntity();
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('L\'utilisateur a été ajouté.'));
                return $this->redirect(['action' => 'add']);
            }
            $this->Flash->error(__('Erreur : Impossible d\'ajouter l\'utilisateur.'));
        }
        $this->set('user', $user);
    }

    public function edit($id)
    {
        $user = $this->Users->get($id);
        $this->set('list_roles', $this->list_roles);
        $this->set(compact('user'));
        if ($this->request->is('post')) {
            /*$user = $this->Users->newEmptyEntity();
            $data = $this->request->getData();
            $user = $this->Users->patchEntity($user, $data);*/
            $data = $this->request->getData();
            $data["role"] = strtolower($data["role"]);
            $user = $this->Users->patchEntity($user, $data);
            //$user = $this->Users->newEntity($data);
            if ($user->getErrors()) {
                var_dump($user->getErrors());
            } else {
                if ($this->Users->save($user)) {
                    $this->Flash->success(__('L\'utilisateur a été modifié.'));
                    return $this->redirect(['action' => 'index']);
                } else {
                    $this->Flash->error(__('Erreur : Impossible de modifier l\'utilisateur.'));
                    return $this->redirect('/users/index');
                }
            }
        }
    }

    public function delete($id)
    {
        $user = $this->Users->get($id);
        $result = $this->Users->delete($user);
        $this->Flash->success(__('L\'utilisateur a été effacé.'));
        return $this->redirect('/users/index');
    }

    public function import()
    {
        
    }

    public function resetPassword($id)
    {
        $user = $this->Users->get($id);
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('Le mot de passe a été changé.'));
                return $this->redirect(['action' => 'add']);
            }
            $this->Flash->error(__('Erreur : Impossible de changer le mot de passe.'));
        }
    }

    public function findUser($email)
    {
        $user = $this->Users->find()
            ->select(['id', 'firstname', 'lastname', 'email', 'password', 'role'])
            ->where(['email' => $email])
            ->first();
        return $user;
    }
}
?>