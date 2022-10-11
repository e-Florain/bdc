<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 * @var \App\View\AppView $this
 */

$cakeDescription = 'Florain - Portail';
?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        <?= $cakeDescription ?>:
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon') ?>

    <link href="https://fonts.googleapis.com/css?family=Raleway:400,700" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/normalize.css@8.0.1/normalize.css">
    <!--<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">-->
   <!--$this->Html->css('milligram.min.css') ?>
  $this->Html->css('cake.css')  -->
    <?= $this->Html->css('materialize.min.css') ?>
    <?= $this->Html->css('material-icons.css') ?>
    <?= $this->Html->css('my.css') ?>
    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>
<body>
<!-- Dropdown Structure -->
<ul id="dropdown1" class="dropdown-content">
  <li>
    <?php
      $session = $this->getRequest()->getSession();
      echo '<a href="/users/reset_password/'.$session->read('User.id').'">Changer le mot de passe</a>';
    ?>
  </li>
  <li class="divider"></li>
  <li><a href="/users/logout">Se déconnecter</a></li>
</ul>

  <nav class="nav-extended">
    <div class="nav-wrapper">
      <img src="/img/logo-monnaie.svg" height="64"><a href="#" class="brand-logo">Florain</a>
      <a href="#" data-target="mobile-demo" class="sidenav-trigger"><i class="material-icons">menu</i></a>
      <ul id="nav-mobile" class="right hide-on-med-and-down">
        <li><a href="/users">Utilisateurs</a></li>
        <li>
          <a class="dropdown-trigger" href="#!" data-target="dropdown1">
            <?php
              $session = $this->getRequest()->getSession();
              echo $session->read('User.name');
            ?>
            <i class="material-icons right">arrow_drop_down</i>
          </a>
        </li>
      </ul>
    </div>
    <div class="nav-content">
      <ul class="tabs tabs-transparent">
        <?php
        if ($session->read('User.role') == "root") {
        ?>
        <li class="tab"><a 
        <?php  if ($this->request->getParam('controller') == "Bdcs" ) {
            echo 'class="active"';
        }
        ?>
        href="/bdcs/index">Bureaux de change</a></li>
        <?php
        }
        ?>
        <li class="tab"><a  
        <?php  if ($this->request->getParam('controller') == "Cashdesks" ) {
            echo 'class="active"';
        }
        ?>
        href="/cashdesks/index">Caisses</a></li>
        <?php
        if ($session->read('User.role') == "root") {
        ?>
        <li class="tab"><a 
        <?php  if ($this->request->getParam('controller') == "Payments" ) {
            echo 'class="active"';
        }
        ?>
        href="/payments/index">Mollie - Payments</a></li>
        <?php
        }
        ?>
        <?php
        if ($session->read('User.role') == "root") {
        ?>
        <li class="tab"><a 
        <?php  if ($this->request->getParam('controller') == "Subscriptions" ) {
            echo 'class="active"';
        }
        ?>
        href="/subscriptions/index">Mollie - Prélèvements</a></li>
        <?php
        }
        ?>
        <?php
        if ($session->read('User.role') == "root") {
        ?>
        <li class="tab"><a 
        <?php  if ($this->request->getParam('controller') == "Payments" ) {
            echo 'class="active"';
        }
        ?>
        href="/payments/onepercent">1%</a></li>
        <?php
        }
        ?>
        <?php
        if ($session->read('User.role') == "root") {
        ?>
        <li class="tab"><a 
        <?php  if ($this->request->getParam('controller') == "Settlements" ) {
            echo 'class="active"';
        }
        ?>
        href="/settlements/index">Mollie - Settlements</a></li>
        <?php
        }
        ?>
      </ul>
    </div>
  </nav>

  <ul class="sidenav" id="mobile-demo">
    <li><a href="/users">Utilisateurs</a></li>
    <li><a href="badges.html"><?php echo $session->read('User.name');?></a></li>
  </ul>

    <main class="main">
        <div class="container">
            <?= $this->Flash->render() ?>
            <?= $this->fetch('content') ?>
        </div>
    </main>
    <footer>
        <?= $this->Html->script('jquery-3.5.1.min'); ?>
        <?= $this->Html->script('materialize.min'); ?>
        <?= $this->Html->script('my'); ?>
    </footer>
    <footer class="page-footer">
      <div class="footer-copyright">
        <div class="container footer-css">
          © 2022 Le Florain
          <!--<a class="right footer-css" href="#!">More Links</a>-->
        </div>    
      </div>
    </footer>
</body>
</html>
