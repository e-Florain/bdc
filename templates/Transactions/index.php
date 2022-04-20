<!-- File: templates/Transactions/index.php -->
<br>
<a class="btn-floating btn-large waves-effect waves-light btn-pink" href="/Transactions/add?cashdesk_id=<?php echo $cashdesk_id; ?>"><i class="material-icons">add</i></a>
<a class="btn-floating btn-large waves-effect waves-light btn-blue" href="/Transactions/importexport"><i class="material-icons">import_export</i></a>
<h3>
    <div id='nbTransactions'>Transactions de la caisse de <?php echo $bdc_name; ?> du <?php echo $cashdesk->date->nice('Europe/Paris', 'fr-FR'); ?>
    <?php if ($trash_view) { 
            echo "effacés";
            echo "(".$nbitems_trashed.")";
        } else {
            echo "(".$nbitems.")";    
        }
    ?>
    </div>
</h3>
<?php
if ($trash_view) {
?>
    <a href="/Transactions/index?cashdesk_id=<?php echo $cashdesk_id; ?>">x Fermer la corbeille</a>
<?php
} else {
?>
    <a href="/Transactions/index/trash:true?cashdesk_id=<?php echo $cashdesk_id; ?>">Corbeille (<?php echo $nbitems_trashed; ?>)</a>
<?php
}
?>
<div id="results">
<table class="striped responsive-table">
    <tr>
        <?php
        foreach ($list_keys as $key=>$item) {
            echo '<th>';
            echo $this->Html->link($item, [
                'controller' => 'Transactions',
                'action' => 'index',
                '?' => ['orderby' => $key]
            ]);
            echo '</th>';
        }
        ?>
        <th></th>
    </tr>

    <!-- Here is where we iterate through our $articles query object, printing out article info -->

    <?php foreach ($transactions as $transaction): ?>
    <tr>
        <td>
            <?php 
            if (isset($transaction->date)) {
                echo $transaction->date->format('Y-m-d');
            }
            ?>
        </td>
        <td>
            <?= $transaction->adh_id ?>
        </td>
        <td>
            <?= $transaction->adh_name ?>
        </td>
        <td>
            <?= $transaction->amount ?>
        </td>
        <td class="icons">
            <a <?php echo 'href="/Transactions/edit/'.$transaction->id.'"'; ?> class="btn-floating btn-large waves-effect waves-light btn-green"><i class="material-icons">edit</i></a>
            <a <?php echo 'href="/Transactions/delete/'.$transaction->id.'"'; ?> class="btn-floating btn-large waves-effect waves-light btn-orange"><i class="material-icons">delete</i></a>
            <?php if ($trash_view): ?>
            <a <?php echo 'href="/Transactions/restore/'.$transaction->id.'"'; ?> class="btn-floating btn-large waves-effect waves-light btn-orange"><i class="material-icons">restore_from_trash</i></a>
            <?php endif; ?>
        </td>

    </tr>
    <?php endforeach; ?>
</table>
<?php
echo '<ul class="pagination">';
echo $this->Paginator->first("<<",array('rel'=>'prev','tag'=>'li'));
if($this->Paginator->hasPrev()){
echo $this->Paginator->prev("<",array('tag'=>'li'));
}
echo $this->Paginator->numbers(array('first' => 2,'last' => 3,'modulus'=> '4','separator' => '','tag'=>'li'));
if($this->Paginator->hasNext()){
    echo $this->Paginator->next(">",array('tag'=>'li'));
}
echo $this->Paginator->last(">>",array('rel'=>'next','tag'=>'li'));
echo '</ul>';
?>
</div>