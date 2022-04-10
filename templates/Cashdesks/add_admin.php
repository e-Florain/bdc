<br>
  <h3>Ajouter une caisse</h3>
  <div class="row">
    <?php echo $this->Form->create(); ?>
    <input name="id" id="id" type="hidden" class="validate">
      <div class="row">
        <div class="input-field col s6">
          <!--<input name="asso" id="asso" required type="text" class="validate">-->
          <select name="bdc_id" >
            <option value="" disabled selected>Choisir</option>
              <?php foreach ($bdcs as $bdc) {
              echo '<option value="'.$bdc['id'].'" >'.$bdc['name'].'</option>';
              }
              ?>
          </select>
          <label for="bdc">Bureau de change</label>
        </div>
      </div>
      <div class="row">
        <div class="input-field col s3">
          <input name="date" type="text" id="date" required class="datepicker">
          <label for="date">Date de la caisse</label>
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
          <label for="amount">Montant initial</label>
        </div>
      </div>
    <button class="btn waves-effect waves-light" type="submit" name="action">Ajouter
    <i class="material-icons right">add</i>
    </button>
    </form>
  </div>
        