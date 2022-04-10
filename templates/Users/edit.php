<br>
<h3>Modifier un utilisateur</h3>
  <div class="row">
    <?php echo $this->Form->create(); ?>
      <div class="row">
        <div class="input-field col s6">
          <input name="firstname" id="first_name" type="text" <?php echo 'value="'.$user->firstname.'"'; ?> class="validate">
          <label for="first_name">Prénom</label>
        </div>
      </div>
      <div class="row">
        <div class="input-field col s6">
          <input name="lastname" id="last_name" type="text" <?php echo 'value="'.$user->lastname.'"'; ?> class="validate">
          <label for="last_name">Nom</label>
        </div>
      </div>
      <div class="row">
        <div class="input-field col s6">
          <input name="email" id="email" type="email" <?php echo 'value="'.$user->email.'"'; ?> class="validate">
          <label for="email">Email</label>
        </div>
      </div>
      <div class="row">
        <div class="input-field col s6">
            <select name="role" >
                <option value="" disabled selected>Choisir</option>
                <?php foreach ($list_roles as $role) {
                  if ($user->role == strtolower($role)) {
                    echo '<option value="'.$role.'" selected>'.$role.'</option>';
                  } else {
                    echo '<option value="'.$role.'" >'.$role.'</option>';
                  }
                }
                ?>
            </select>
            <label>Rôle</label>
        </div>
      </div>
    <button class="btn waves-effect waves-light" type="submit" name="action">Modifier
    <i class="material-icons right">edit</i>
    </button>
    </form>
  </div>
