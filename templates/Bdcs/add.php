<br>
<h3>Ajouter un bureau de change</h3>
  <div class="row">
    <?php echo $this->Form->create(); ?>
      <div class="row">
        <div class="input-field col s6">
          <input name="name" id="name" type="text" required class="validate">
          <label for="name">Nom du bureau de change</label>
        </div>
      </div>
    <button class="btn waves-effect waves-light" type="submit" name="action">Ajouter
    <i class="material-icons right">send</i>
    </button>
    </form>
  </div>
        