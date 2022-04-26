<br>
<h3>Ajouter une transaction</h3>
  <div class="row">
    <?php echo $this->Form->create(); ?>
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
          <input name="date" type="text" id="date_adh" required class="datepicker">
          <label for="date">Date de la tranaction</label>
        </div>
      </div>
      <div class="row">
        <div class="input-field col s6">
          <input name="adh_name" id="adh_name" required type="text" class="validate">
          <label for="adh_name">Nom d'adhérent</label>
        </div>
        <div class="input-field col s1">
          <a class="btn btn-floating suffix" onclick="searchAdhByName();"><i class="material-icons">search</i></a>
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
        