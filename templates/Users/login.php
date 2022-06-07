<!-- in /templates/Users/login.php -->
<br><br>
<?php echo $this->Flash->render() ?>
    <h3>Login</h3>
    <?php echo $this->Form->create() ?>
<div class="row">


    <!--<form class="col s4" methode="post" action="/users/login">-->
      <div class="row">
        <div class="input-field col s6">
          <input name="email" id="email" type="text" class="required validate">
          <label for="email">Email</label>
        </div>
      </div>
      <div class="row">
        <div class="input-field col s6">
          <input name="password" id="password" type="password" class="required validate">
          <label for="password">Password</label>
        </div>
      </div>
      <button class="btn waves-effect waves-light" type="submit" name="action">Login
        <i class="material-icons right">send</i>
      </button>
    </form>
</div>