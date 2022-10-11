<br>
  <h3>Ajouter un prélèvement</h3>
  <div class="row">
    <?php echo $this->Form->create(); ?>
    <input name="id" id="id" type="hidden" class="validate">
      <div class="row">
        <div class="input-field col s6">
          <!--<input name="asso" id="asso" required type="text" class="validate">-->
          <select name="client_id" >
            <option value="" disabled selected>Choisir un client</option>
              <?php foreach ($customers as $customer) {
              echo '<option value="'.$customer['email'].'" >'.$customer['name'].' - '.$customer['email'].'</option>';
              }
              ?>
          </select>
          <label for="client">Client</label>
        </div>
      </div>
      <div class="row">
        <div class="input-field col s6">
          <!--<input name="asso" id="asso" required type="text" class="validate">-->
          <select name="interval" >
            <option value="" disabled selected>Choisir un fréquence</option>
              <?php 
                echo '<option value="monthly">Mensuel</option>';
                echo '<option value="annually">Annuel</option>';
              ?>
          </select>
          <label for="client">Fréquence</label>
        </div>
      </div>
      <div class="row">
        <div class="input-field col s6">
          <input name="description" id="description" type="text" required class="validate">
          <label for="description">Description</label>
        </div>
      </div>
      <div class="row">
        <div class="input-field col s6">
          <input name="amount" id="amount" type="text" required class="validate">
          <label for="amount">Montant</label>
        </div>
      </div>
    <button class="btn waves-effect waves-light" type="submit" name="action">Ajouter
    <i class="material-icons right">add</i>
    </button>
    </form>
  </div>
        