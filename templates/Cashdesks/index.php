<!-- File: templates/Cashdesks/index.php -->
<br>
<?php if ($role == "root") { ?>
    <a class="btn-floating btn-large waves-effect waves-light btn-pink" href="/cashdesks/add_admin"><i class="material-icons">add</i></a>
<?php } else { ?>
<a class="btn-floating btn-large waves-effect waves-light btn-pink" href="/cashdesks/add"><i class="material-icons">add</i></a>
<?php } ?>
<a class="btn-floating btn-large waves-effect waves-light btn-blue" href="/cashdesks/importexport"><i class="material-icons">import_export</i></a>
<h3>
    <div id='nbcashdesks'>Caisses
    <?php if ($trash_view) { 
            echo "effacées";
            echo "(".$nbitems_trashed.")";
        }
        else if ($close_view) {
            echo "fermées";
            echo "(".$nbitems_closed.")";
        } else {
            echo "(".$nbitems.")";    
        }
    ?>
    </div>
</h3>
<?php
if ($trash_view) {
?>
    <a href="/cashdesks/index">x Fermer la corbeille</a>
<?php
} else {
?>
    <a href="/cashdesks/index/trash:true">Corbeille (<?php echo $nbitems_trashed; ?>)</a>
<?php
}

if ($close_view) {
?>
    <a href="/cashdesks/index">Caisses ouvertes (<?php echo $nbitems; ?>)</a>
<?php
} else {
?>
    <a href="/cashdesks/index/close:true">Caisses fermées (<?php echo $nbitems_closed; ?>)</a>
<?php
}
?>

<table class="striped responsive-table">
    <tr>
        <th><?= $this->Html->link("Id", [
            'controller' => 'cashdesks',
            'action' => 'index',
            '?' => ['orderby' => "id"]
        ]); ?>
        </th>
        <th><?= $this->Html->link("Date", [
            'controller' => 'cashdesks',
            'action' => 'index',
            '?' => ['orderby' => "date"]
        ]); ?>
        </th>
        <?php if ($role == "root") {
        ?>
        <th><?= $this->Html->link("Bdc", [
            'controller' => 'cashdesks',
            'action' => 'index',
            '?' => ['orderby' => "bdc_id"]
        ]); ?>
        </th>
        <?php
        }
        ?>
        <!--
        <th><?= $this->Html->link("Description", [
            'controller' => 'cashdesks',
            'action' => 'index',
            '?' => ['orderby' => "description"]
        ]); ?>
        </th>-->
        <th><?= $this->Html->link("Montant initial", [
            'controller' => 'cashdesks',
            'action' => 'index',
            '?' => ['orderby' => "received_amount"]
        ]); ?>
        </th>
        <th><?= $this->Html->link("Solde", [
            'controller' => 'cashdesks',
            'action' => 'index',
            '?' => ['orderby' => "balance"]
        ]); ?>
        </th>
        <th><?= $this->Html->link("Solde Euros", [
            'controller' => 'cashdesks',
            'action' => 'index',
            '?' => ['orderby' => "balance_euros"]
        ]); ?>
        </th>
        <th></th>
    </tr>

    <!-- Here is where we iterate through our $articles query object, printing out article info -->

    <?php foreach ($cashdesks as $cashdesk): ?>
    <tr>
        <td>
            <?php echo $cashdesk->id; ?>
        </td>
        <td>
            <?= $cashdesk->date ?>
        </td>
        <td>
            <?php
            foreach ($bdcs as $bdc) {
                if ($bdc->id == $cashdesk->bdc_id) {
                    echo $bdc->name;
                }
            } 
            ?>
        </td>
        <!--<td>
            <?= $cashdesk->description ?>
        </td>-->
        <td>
            <?= $cashdesk->received_amount ?>
        </td>
        <td>
            <?= $cashdesk->balance ?>
        </td>
        <td>
            <?= $cashdesk->balance_euros ?>
        </td>
        <td class="icons"> 
            <a <?php echo 'href="/cashdesks/edit/'.$cashdesk->id.'"'; ?> data-position="top" data-tooltip="Editer la caisse" class="btn-floating btn-large waves-effect waves-light btn-green tooltipped"><i class="material-icons">edit</i></a>
            <a <?php echo 'href="/transactions/index?cashdesk_id='.$cashdesk->id.'"'; ?> data-position="top" data-tooltip="Voir la caisse"  class="btn-floating btn-large waves-effect waves-light btn-green tooltipped"><i class="material-icons">attach_money</i></a>
            <a <?php echo 'href="/cashdesks/delete/'.$cashdesk->id.'"'; ?> data-position="top" data-tooltip="Effacer la caisse"  class="btn-floating btn-large waves-effect waves-light btn-orange tooltipped"><i class="material-icons">delete</i></a>
            <?php if (!$close_view): ?>
            <a <?php echo 'href="/cashdesks/close/'.$cashdesk->id.'"'; ?> data-position="top" data-tooltip="Fermer la caisse" class="btn-floating btn-large waves-effect waves-light btn-green tooltipped"><i class="material-icons">close</i></a>
            <?php endif; ?>
            <?php if ($close_view): ?>
            <a <?php echo 'href="/cashdesks/unclose/'.$cashdesk->id.'"'; ?> data-position="top" data-tooltip="Rouvrir la caisse" class="btn-floating btn-large waves-effect waves-light btn-green tooltipped"><i class="material-icons">open_in_new</i></a>
            <?php endif; ?>
            <?php if ($trash_view): ?>
            <a <?php echo 'href="/cashdesks/restore/'.$cashdesk->id.'"'; ?> data-position="top" data-tooltip="Restaurer la caisse" class="btn-floating btn-large waves-effect waves-light btn-orange tooltipped"><i class="material-icons">restore_from_trash</i></a>
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
