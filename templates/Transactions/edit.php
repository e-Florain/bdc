<br>
  <div class="row">
    <?php echo $this->Form->create(); ?>
    <input name="id" id="id" <?php echo 'value="'.$adh->id.'"'; ?> type="hidden" class="validate">
      <div class="row">
        <div class="input-field col s2">
          <input name="adh_id" id="adh_id" <?php echo 'value="'.$adh->adh_id.'"'; ?> type="text" class="validate">
          <label for="adh_id">Numéro d'adhérent</label>
        </div>
        <div class="input-field col s3">
          <input name="date_adh" type="text" <?php echo 'value="'.$adh->date_adh->format('Y-m-d').'"'; ?> id="date_adh" class="datepicker">
          <label for="date_adh">Date d'adhésion</label>
        </div>
      </div>
      <div class="row">
        <div class="input-field col s6">
          <select multiple name="adh_years[]">
            <option value="" disabled>Choisir</option>
            <?php
            $infos = explode(";", $adh->adh_years);
            $tmp=true;
            if (date("W")>=44){
              echo '<option ';
              if (in_array((date("Y")+1), $infos)) {
                echo 'selected';
              }
              echo ' value="'.(date("Y")+1).'">'.(date("Y")+1).'</option>';
              $tmp=false;
            }

            echo '<option ';
            if (in_array((date("Y")), $infos)) {
              echo 'selected';
            }
            echo ' value="'.date("Y").'">'.date("Y").'</option>';

            echo '<option ';
            if (in_array((date("Y")-1), $infos)) {
              echo 'selected';
            }
            echo ' value="'.(date("Y")-1).'">'.(date("Y")-1).'</option>';
            if ($tmp) {
              echo '<option ';
              if (in_array((date("Y")-2), $infos)) {
                echo 'selected';
              }
              echo ' value="'.(date("Y")-2).'">'.(date("Y")-2).'</option>';
            }
            ?>
          </select>
          <label>Années d'adhésion</label>
        </div>
      </div>
      <div class="row">
        <div class="input-field col s6">
          <input name="firstname" id="first_name" <?php echo 'value="'.$adh->firstname.'"'; ?> type="text" class="validate">
          <label for="first_name">Prénom</label>
        </div>
        <div class="input-field col s6">
          <input name="lastname" id="last_name" <?php echo 'value="'.$adh->lastname.'"'; ?> type="text" class="validate">
          <label for="last_name">Nom</label>
        </div>
      </div>
      <div class="row">
        <div class="input-field col s6">
          <input name="email" id="email" <?php echo 'value="'.$adh->email.'"'; ?> type="email" class="validate">
          <label for="email">Email</label>
        </div>
      </div>
      <div class="row">
        <div class="input-field col s6">
          <input name="city" id="city" <?php echo 'value="'.$adh->city.'"'; ?> type="text" class="validate">
          <label for="city">Ville</label>
        </div>
      </div>
      <div class="row">
        <div class="input-field col s6">
          <input name="phonenumber" id="phonenumber" <?php echo 'value="'.$adh->phonenumber.'"'; ?> type="text" class="validate">
          <label for="phonenumber">Téléphone</label>
        </div>
      </div>
      <div class="row">
        <div class="input-field col s6">
          <!--<input name="asso" id="asso" <?php //echo 'value="'.$adh->asso.'"'; ?> type="text" class="validate">-->
          <select name="asso_id" >
            <option value="" disabled >Choisir</option>
              <?php foreach ($assos as $asso) {
                if ($adh->asso_id == $asso['id']) {
                  echo '<option value="'.$asso['id'].'" selected>'.$asso['asso_id']." - ".$asso['name'].'</option>';
                } else {
                  echo '<option value="'.$asso['id'].'" >'.$asso['asso_id']." - ".$asso['name'].'</option>';
                }
              }
            ?>
          </select>
          <label for="asso">Association</label>
        </div>
      </div>
      <div class="row">
        <div class="input-field col s6">
            <select name="payment_type" >
                <option value="" disabled >Choisir</option>
                <?php foreach ($list_payment_type as $payment_type) {
                  if ($adh->payment_type == $payment_type) {
                    echo '<option value="'.$payment_type.'" selected>'.$payment_type.'</option>';
                  } else {
                    echo '<option value="'.$payment_type.'" >'.$payment_type.'</option>';
                  }
                }
                ?>
            </select>
            <label>Type de paiement</label>
        </div>
      </div>
      <div class="row">
        <div class="input-field col s6">
          <input name="amount" id="amount" type="text" <?php echo 'value="'.$adh->amount.'"'; ?> class="validate">
          <label for="amount">Montant (entre 5 et 50)</label>
        </div>
      </div>
      <p>
        &nbsp;<label>
            <input name="cyclos_account" id="cyclos_account" <?php if ($adh->cyclos_account) { echo "checked"; } ?> type="checkbox" />
            <span>Compte Cyclos</span>
        </label>
      </p>
      <p>
        &nbsp;<label>
            <input name="newsletter" id="newsletter" <?php if ($adh->newsletter) { echo "checked"; } ?> type="checkbox" />
            <span>Ajout Newsletter</span>
        </label>
      </p>
    <button class="btn waves-effect waves-light" type="submit" name="action">Modifier
    <i class="material-icons right">send</i>
    </button>
    </form>
  </div>
        