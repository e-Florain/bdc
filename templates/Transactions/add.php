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
          <a class="btn btn-floating material-icons suffix" onclick="searchAdhById();">search</a>
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
          <label for="name">Nom d'adhérent</label>
        </div>
      </div>
      <div class="row">
        <div class="input-field col s6">
          <input name="amount" id="amount" required type="text" class="validate">
          <label for="amount">Montant</label>
        </div>
      </div>
    <button class="btn waves-effect waves-light" required type="submit" name="action">Ajouter
    <i class="material-icons right">send</i>
    </button>
    </form>
  </div>
        