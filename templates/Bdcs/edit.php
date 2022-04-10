<br>
  <div class="row">
    <?php echo $this->Form->create(); ?>
    <input name="id" id="id" <?php echo 'value="'.$bdc->id.'"'; ?> type="hidden" class="validate">
      <div class="row">
        <div class="input-field col s6">
          <input name="orga_name" id="orga_name" type="text" <?php echo 'value="'.$bdc->name.'"'; ?> class="validate">
          <label for="orga_name">Nom du bureau de change</label>
        </div>
      </div>
      <div class="row">
        <div class="input-field col s6">
          <input name="address" id="address" type="text" <?php echo 'value="'.$bdc->address.'"'; ?> class="validate">
          <label for="address">Adresse</label>
        </div>
      </div>
      <div class="row">
        <div class="input-field col s6">
          <input name="postcode" id="postcode" type="text" <?php echo 'value="'.$bdc->postcode.'"'; ?> class="validate">
          <label for="postcode">Code Postal</label>
        </div>
      </div>
      <div class="row">
        <div class="input-field col s6">
          <input name="city" id="city" type="text" <?php echo 'value="'.$bdc->city.'"'; ?> class="validate">
          <label for="city">Ville</label>
        </div>
      </div>
      <div class="row">
        <div class="input-field col s6">
          <input name="phonenumber" id="phonenumber" type="text" <?php echo 'value="'.$bdc->phonenumber.'"'; ?> class="validate">
          <label for="phonenumber">Téléphone</label>
        </div>
      </div> 
      
    <button class="btn waves-effect waves-light" type="submit" name="action">Modifier
    <i class="material-icons right">send</i>
    </button>
    </form>
  </div>
        