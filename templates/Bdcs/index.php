<!-- File: templates/bdcs/index.php -->

<br>
<a class="btn-floating btn-large waves-effect waves-light btn-pink" href="/bdcs/syncBdcs"><i class="material-icons">autorenew</i></a>
<!--<a class="btn-floating btn-large waves-effect waves-light btn-pink" href="/bdcs/add"><i class="material-icons">add</i></a>-->
<!--<a class="btn-floating btn-large waves-effect waves-light btn-blue" href="/bdcs/importexport"><i class="material-icons">import_export</i></a>-->
<h3>
    <div id='nbbdcs'>Bureaux de change 
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
    <a href="/bdcs/index">x Fermer la corbeille</a>
<?php
} else {
?>
    <a href="/bdcs/index/trash:true">Corbeille (<?php echo $nbitems_trashed; ?>)</a>
<?php
}
?>
<form class="col s12">
    <div class="row">
        <div class="input-field col s6">
            <i class="material-icons prefix">search</i>
            <input type="text" id="filter_bdcs_text"></textarea>
            <label for="icon_prefix2"></label>
        </div>
    </div>
</form>
<div id="results">
<table class="striped responsive-table">
    <tr>
        <th>
            <label>
            <input type="checkbox" id="selectAll"/>
            <span></span>
            </label>
        </th>
        <th><?= $this->Html->link("Id", [
            'controller' => 'bdcs',
            'action' => 'index',
            '?' => ['orderby' => "id"]
        ]); ?>
        </th>
        <th><?= $this->Html->link("Nom", [
            'controller' => 'bdcs',
            'action' => 'index',
            '?' => ['orderby' => "name"]
        ]); ?>
        </th>
        <th><?= $this->Html->link("Adresse", [
            'controller' => 'bdcs',
            'action' => 'index',
            '?' => ['orderby' => "address"]
        ]); ?>
        </th>
        <th><?= $this->Html->link("Code postal", [
            'controller' => 'bdcs',
            'action' => 'index',
            '?' => ['orderby' => "postcode"]
        ]); ?>
        </th>
        <th><?= $this->Html->link("Ville", [
            'controller' => 'bdcs',
            'action' => 'index',
            '?' => ['orderby' => "city"]
        ]); ?>
        </th>
        <th><?= $this->Html->link("Tel", [
            'controller' => 'bdcs',
            'action' => 'index',
            '?' => ['orderby' => "phonenumber"]
        ]); ?>
        </th>
        <th></th>
    </tr>

    <!-- Here is where we iterate through our $articles query object, printing out article info -->

    <?php foreach ($bdcs as $bdc): ?>
    <tr>
        <td>
            <label>
            <input type="checkbox" id="<?php echo $bdc->id; ?>" name="<?php echo $bdc->id; ?>"/>
            <span></span>
            </label>
        </td>
        <td>
            <?php echo $bdc->id; ?>
        </td>
        <td>
            <?= $bdc->name ?>
        </td>
        <td>
            <?= $bdc->address ?>
        </td>
        <td>
            <?= $bdc->postcode ?>
        </td>
        <td>
            <?= $bdc->city ?>
        </td>
        <td>
            <?= $bdc->phonenumber ?>
        </td>
        <td class="icons">
            <!--<a <?php echo 'href="/bdcs/view/'.$bdc->id.'"'; ?> class="btn-floating waves-effect waves-light btn-green"><i class="material-icons">remove_red_eye</i></a>-->
            <a <?php echo 'href="/bdcs/delete/'.$bdc->id.'"'; ?> class="btn-floating waves-effect waves-light btn-orange"><i class="material-icons">delete</i></a>
            <?php if ($trash_view): ?>
            <a <?php echo 'href="/bdcs/restore/'.$bdc->id.'"'; ?> class="btn-floating waves-effect waves-light btn-orange"><i class="material-icons">restore_from_trash</i></a>
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
