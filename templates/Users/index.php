<!-- File: templates/Users/index.php -->
<br>
<a class="btn-floating btn-large waves-effect waves-light btn-pink" href="/users/add"><i class="material-icons">add</i></a>
<h3>Utilisateurs</h3>
<table class="striped responsive-table">
    <tr>
        <th><?= $this->Html->link("Nom", [
            'controller' => 'users',
            'action' => 'index',
            '?' => ['orderby' => "lastname"]
        ]); ?>
        </th>
        <th><?= $this->Html->link("Prénom", [
            'controller' => 'users',
            'action' => 'index',
            '?' => ['orderby' => "firstname"]
        ]); ?>
        </th>
        <th><?= $this->Html->link("Email", [
            'controller' => 'users',
            'action' => 'index',
            '?' => ['orderby' => "email"]
        ]); ?>
        </th>
        <th><?= $this->Html->link("Bdc", [
            'controller' => 'users',
            'action' => 'index',
            '?' => ['orderby' => "bdc_id"]
        ]); ?>
        <th><?= $this->Html->link("Role", [
            'controller' => 'users',
            'action' => 'index',
            '?' => ['orderby' => "role"]
        ]); ?>
        </th>
        <th></th>
    </tr>

    <!-- Here is where we iterate through our $articles query object, printing out article info -->

    <?php 
    //var_dump($bdcs);
    foreach ($users as $user): ?>
    <tr>
        <td>
            <?= $user->lastname ?>
        </td>
        <td>
            <?= $user->firstname ?>
        </td>
        <td>
            <?= $user->email ?>
        </td>
        <td>
            <?php
            $found = false;
            foreach ($bdcs as $bdc) {
                if ($user->bdc_id == $bdc->id) {
                    echo $bdc->name;
                    $found = true;
                }
            }
            if (!$found) {
                echo "TOUS";
            }
            ?>
        </td>
        <td>
            <?= $user->role ?>
        </td>
        <td class="icons">
            <a <?php echo 'href="/users/edit/'.$user->id.'"'; ?> class="btn-floating btn-large waves-effect waves-light btn-green"><i class="material-icons">edit</i></a>
            <a <?php echo 'href="/users/reset_password/'.$user->id.'"'; ?> class="btn-floating btn-large waves-effect waves-light btn-blue"><i class="material-icons">refresh</i></a>
            <a <?php echo 'href="/users/delete/'.$user->id.'"'; ?> class="btn-floating btn-large waves-effect waves-light btn-orange"><i class="material-icons">delete</i></a>
        </td>

    </tr>
    <?php endforeach; ?>
</table>