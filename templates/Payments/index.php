<!-- File: templates/payments/index.php -->
<br>
<h3>
    <div id='nbpayments'>Paiments (<?php echo $nbpayments; ?>)</div>
</h3>

<!--<form class="col s12">
    <div class="row">
        <div class="input-field col s6">
            <i class="material-icons prefix">search</i>
            <input type="text" id="filter_payments_text"></textarea>
            <label for="icon_prefix2"></label>
        </div>
    </div>
</form>-->
<div id="results">
<table class="striped responsive-table">
    <tr>
        <th><?= $this->Html->link("Id", [
            'controller' => 'payments',
            'action' => 'index',
            '?' => ['orderby' => "id"]
        ]); ?>
        </th>
        <th><?= $this->Html->link("Date", [
            'controller' => 'payments',
            'action' => 'index',
            '?' => ['orderby' => "createdAt"]
        ]); ?>
        </th>
        <th><?= $this->Html->link("Description", [
            'controller' => 'payments',
            'action' => 'index',
            '?' => ['orderby' => "description"]
        ]); ?>
        </th>
        <th><?= $this->Html->link("Montant", [
            'controller' => 'payments',
            'action' => 'index',
            '?' => ['orderby' => "amount"]
        ]); ?>
        </th>
        <th><?= $this->Html->link("Status", [
            'controller' => 'payments',
            'action' => 'index',
            '?' => ['orderby' => "status"]
        ]); ?>
        </th>
        <th><?= $this->Html->link("Client", [
            'controller' => 'payments',
            'action' => 'index',
            '?' => ['orderby' => "customerId"]
        ]); ?>
        </th>
        <th></th>
    </tr>

    <!-- Here is where we iterate through our $articles query object, printing out article info -->

    <?php foreach ($list_payments as $payment): ?>
    <tr>
        <td>
            <?php echo $payment['id']; ?>
        </td>
        <td>
            <?php echo $payment['createdAt']; ?>
        </td>
        <td>
            <?php echo $payment['description']; ?>
        </td>
        <td>
            <?php echo $payment['amount']['value']; ?>
        </td>
        <td>
            <?php echo $payment['status']; ?>
        </td>
        <td>
            <?php 
            if (isset($payment['customerId'])) {
                echo $payment['customerId'];
            } else {
                echo 'None';
            }
            ?>
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
