<br>
<a class="btn-floating btn-large waves-effect waves-light btn-pink" onclick = "history.back()"><i class="material-icons">arrow_back</i></a>
<h3>Ajouter une transaction</h3>
  <div class="row">
    <?php echo $this->Form->create(null, ['autocomplete' => 'off', 'onSubmit' => 'submitFunction(event);']); ?>
      <div class="row">
        <input type="hidden" name="cashdesk_id" id="cashdesk_id" value="<?php echo $cashdesk_id; ?>">
        <div class="input-field col s2">
          <input name="adh_id" id="adh_id" type="text" required class="validate">
          <label for="adh_id">Numéro d'adhérent</label>
        </div>
        <div class="input-field col s1">
          <a class="btn btn-floating suffix" onclick="searchAdhById();"><i class="material-icons">search</i></a>
        </div>
      </div>
      <div class="row">  
        <div class="input-field col s3">
          <input name="date" type="text" id="date" value="<?php echo $date; ?>" required class="datepicker validate">
          <label for="date">Date de la transaction</label>
        </div>
      </div>
      <div class="row">
        <div class="input-field col s6">
          <input name="adh_lastname" id="adh_lastname" required type="text" class="validate">
          <label for="adh_lastname">Nom d'adhérent</label>
        </div>
        <div class="input-field col s1">
          <a class="btn btn-floating suffix" onclick="searchAdhByName();"><i class="material-icons">search</i></a>
        </div>
      </div>
      <div class="row">
        <div class="input-field col s6">
          <input name="adh_firstname" id="adh_firstname" required type="text" class="validate">
          <label for="adh_firstname">Prénom d'adhérent</label>
          <!--<select name="adh_firstname" id="adh_firstname">
          </select>-->
          <label>Prénom d'adhérent</label>
        </div>
      </div>

      <div class="row">
        <div class="input-field col s6">
          <select name="adh_fullname" id="adh_fullname">
          </select>
          <label>Nom</label>
        </div>
      </div>
      
      <div class="row">
        <div class="input-field col s6" id="statusadh">
        </div>
      </div>
      <div class="row">
        <div class="input-field col s6">
          <input name="amount" id="amount" required type="text" class="validate">
          <label for="amount">Montant</label>
        </div>
      </div>
      <div class="row">
        <div class="input-field col s6">
            <select name="payment_type" >
                <option value="" disabled selected>Choisir</option>
                <?php foreach ($list_payment_type as $payment_type) {
                  echo '<option value="'.$payment_type.'" >'.$payment_type.'</option>';
                }
                ?>
            </select>
            <label>Type de paiement</label>
        </div>
      </div>
    <button class="btn waves-effect waves-light" id="btn_add" required type="submit" name="action">Ajouter
    <i class="material-icons right">send</i>
    </button>
    </form>
  </div>
        