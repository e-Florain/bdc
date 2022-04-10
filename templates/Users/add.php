<br>
<h3>Ajouter un utilisateur</h3>
  <div class="row">
    <?php echo $this->Form->create(); ?>
      <div class="row">
        <div class="input-field col s6">
          <input name="firstname" id="first_name" type="text" class="validate">
          <label for="first_name">Prénom</label>
        </div>
      </div>
      <div class="row">
        <div class="input-field col s6">
          <input name="lastname" id="last_name" type="text" class="validate">
          <label for="last_name">Nom</label>
        </div>
      </div>
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
      <div class="row">
        <div class="input-field col s6">
          <input name="email" id="email" type="email" class="validate">
          <label for="email">Email</label>
        </div>
      </div>
      <div class="row">
        <div class="input-field col s6">
            <select name="role" >
                <option value="" disabled selected>Choisir</option>
                <?php foreach ($list_roles as $role) {
                  echo '<option value="'.strtolower($role).'" >'.$role.'</option>';
                }
                ?>
            </select>
            <label>Rôle</label>
        </div>
      </div>
    <button class="btn waves-effect waves-light" type="submit" name="action">Ajouter
    <i class="material-icons right">send</i>
    </button>
    </form>
  </div>
