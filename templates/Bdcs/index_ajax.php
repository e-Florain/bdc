Adhérents pros;<?php echo $trash_view.";".$nbitems; ?>;
<table class="striped responsive-table">
    <tr>
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
            <?php echo $bdc->id; ?>
        </td>
        <td>
            <?= $bdc->name ?>
        </td>
        <td>
            <?= $bdc->phonenumber ?>
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
        <td class="icons">
            <a <?php echo 'href="/bdcs/delete/'.$bdc->id.'"'; ?> class="btn-floating btn-large waves-effect waves-light btn-orange"><i class="material-icons">delete</i></a>
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