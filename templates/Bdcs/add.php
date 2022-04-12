<br>
<h3>Ajouter un bureau de change</h3>
  <div class="row">
    <?php echo $this->Form->create(); ?>
    <div class="row">
        <div class="input-field col s6">
          <select>
            <option value="" disabled selected>Choose your option</option>
            <?php foreach ($adhpros as $adhpro) {
              echo '<option value="'.$adhpro['name'].'">'.$adhpro['name'].'</option>';
            }
            ?>
          </select>
          <label>Materialize Select</label>
        </div>
      </div>
      <div class="row">
        <div class="input-field col s6">
          <input name="name" id="name" type="text" required class="validate">
          <label for="name">Nom du bureau de change</label>
        </div>
        <div class="input-field col s1">
          <a class="btn btn-floating material-icons suffix" onclick="searchBdcByName();">search</a>
        </div>
      </div>
      <div class="row">
        <div class="input-field col s6">
          <input name="address" id="address" type="text" required class="validate">
          <label for="address">Adresse</label>
        </div>
      </div>
      <div class="row">
        <div class="input-field col s6">
          <input name="postcode" id="postcode" type="text" required class="validate">
          <label for="postcode">Code Postal</label>
        </div>
      </div>
      <div class="row">
        <div class="input-field col s6">
          <input name="city" id="city" type="text" required class="validate">
          <label for="city">Ville</label>
        </div>
      </div>
      <div class="row">
        <div class="input-field col s6">
          <input name="phonenumber" id="phonenumber" type="text" required class="validate">
          <label for="phonenumber">Téléphone</label>
        </div>
      </div>
      
    <button class="btn waves-effect waves-light" type="submit" name="action">Ajouter
    <i class="material-icons right">send</i>
    </button>
    </form>
  </div>
        