<!-- File: templates/Adhpros/import.php -->
<br>
<h3>Importation</h3>
<table class="striped responsive-table">
    <tr>
        <?php
        foreach ($list_keys as $key=>$keyname) {
            echo "<th>".$keyname."</th>";
        }
        echo "<th>Imported</th>";
        ?>
    </tr>
    <?php
    foreach($uploadDatas as $data) {
        echo "<tr>";
        foreach ($list_keys as $key=>$keyname) {
            echo "<td>".$data[$key]."</td>";
        }
        echo "<td>";
        if ($data["imported"]) {
            echo '<span class="material-icons">check_circle</span>';
        } else {
            echo '<span class="material-icons tooltipped" data-position="top" data-tooltip="'.$data["msgerr"].'">error</span>';
        }
        //.$data['imported'].
        echo "</td>";
        echo "</tr>";
    }

    ?>
</table>
<center>
<a href="/adhpros/index" class="btn waves-effect waves-light" type="submit" name="action">Retour
    <i class="material-icons right">keyboard_backspace</i>
</a>
</center>