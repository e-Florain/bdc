<!-- File: templates/payments/index.php -->

<br>
<h3>
    <div id='nbpayments'>1%</div>
</h3>

<div id="results">
<table class="striped responsive-table">
    <tr>
        <th>Association</th>
        <th>Montant</th>
        <th>1%</th>
    </tr>

    <!-- Here is where we iterate through our $articles query object, printing out article info -->

    <?php foreach ($listpaymentsbyassos as $key=>$amount): ?>
        <?php $percent = floatval($amount)/100; ?>
    <tr>
        <td>
            <?php echo $listassos[$key]; ?>
        </td>
        <td>
            <?php echo $amount; ?>
        </td>
        <td>
            <?php echo $percent; ?>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
