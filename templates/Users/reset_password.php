<br>
<h3>Change un mot de passe</h3>
  <div class="row">
    <?php echo $this->Form->create(); ?>
    <form class="col s12" methode="post" action="/users/reset_password">
      <div class="row">
        <div class="input-field col s6">
          <input name="password" id="password" type="password" class="validate">
          <label for="password">Mot de passe </label>
        </div>
      </div>
      <div class="row">
        <div class="input-field col s6">
          <input name="password2" id="password2" type="password" class="validate">
          <label for="password2">Mot de passe (confirmation)</label>
        </div>
      </div>
    <button class="btn waves-effect waves-light" type="submit" name="action">Modifier
    <i class="material-icons right">send</i>
    </button>
    </form>
  </div>
