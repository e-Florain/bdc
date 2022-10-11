<!-- File: templates/subscriptions/index.php -->
<br>
<?php if ($role == "root") { ?>
    <a class="btn-floating btn-large waves-effect waves-light btn-pink" href="/subscriptions/add"><i class="material-icons">add</i></a>
<?php } ?>
<br>
<h3>
    <div id='nbsubscriptions'>Prélèvements (<?php echo $nbsubscriptions; ?>)</div>
</h3>
<!--
<form class="col s12">
    <div class="row">
        <div class="input-field col s6">
            <i class="material-icons prefix">search</i>
            <input type="text" id="filter_subscriptions_text"></textarea>
            <label for="icon_prefix2"></label>
        </div>
    </div>
</form>-->
<div id="results">
<table class="striped responsive-table">
    <tr>
        <th><?= $this->Html->link("Id", [
            'controller' => 'subscriptions',
            'action' => 'index',
            '?' => ['orderby' => "id"]
        ]); ?>
        </th>
        <th><?= $this->Html->link("Description", [
            'controller' => 'subscriptions',
            'action' => 'index',
            '?' => ['orderby' => "description"]
        ]); ?>
        </th>
        <th><?= $this->Html->link("Montant", [
            'controller' => 'subscriptions',
            'action' => 'index',
            '?' => ['orderby' => "amount"]
        ]); ?>
        </th>
        <th><?= $this->Html->link("Intervale", [
            'controller' => 'subscriptions',
            'action' => 'index',
            '?' => ['orderby' => "interval"]
        ]); ?>
        </th>
        <th><?= $this->Html->link("Status", [
            'controller' => 'subscriptions',
            'action' => 'index',
            '?' => ['orderby' => "status"]
        ]); ?>
        </th>
        <th><?= $this->Html->link("Nom", [
            'controller' => 'subscriptions',
            'action' => 'index',
            '?' => ['orderby' => "name"]
        ]); ?>
        </th>
        <th><?= $this->Html->link("Email", [
            'controller' => 'subscriptions',
            'action' => 'index',
            '?' => ['orderby' => "email"]
        ]); ?>
        </th>
        <th></th>
    </tr>

    <!-- Here is where we iterate through our $articles query object, printing out article info -->

    <?php foreach ($subscriptions as $subscription): ?>
    <tr>
        <td>
            <?php echo '<a href="/subscriptions/view/'.$subscription['customerId'].'/'.$subscription['id'].'" >'.$subscription['id'].'</a>'; ?>
        </td>
        <td>
            <?php echo $subscription['description']; ?>
        </td>
        <td>
            <?php echo $subscription['amount']['value']; ?>
        </td>
        <td>
            <?php echo $subscription['interval']; ?>
        </td>
        <td>
            <?php echo $subscription['status']; ?>
        </td>
        <td>
            <?php echo $list_customers[$subscription['customerId']]['name']; ?>
        </td>
        <td>
            <?php echo $list_customers[$subscription['customerId']]['email']; ?>
        </td>
        <td>
            <?php echo '<a href="/subscriptions/delete?subscription_id='.$subscription['id'].'&customer_id='.$subscription['customerId'].'"><i class="material-icons">delete</i></a>'; ?>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
<center>
<ul class="pagination">
<?php
if (isset($prevfrom)) {
    echo '<li class="waves-effect"><a href="/payments/index/'.$prevfrom.'"><i class="material-icons">chevron_left</i></a></li>';
}
if (isset($nextfrom)) {
    echo '<li class="waves-effect"><a href="/payments/index/'.$nextfrom.'"><i class="material-icons">chevron_right</i></a></li>';
}
?>
</ul>
</center>