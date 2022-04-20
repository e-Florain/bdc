<br>
  <div class="row">
    <?php echo $this->Form->create(); ?>
    <input name="id" id="id" <?php echo 'value="'.$cashdesk->id.'"'; ?> type="hidden" class="validate">
      <div class="row">
        <div class="input-field col s6">
          <input name="description" id="description" <?php echo 'value="'.$cashdesk->description.'"'; ?> type="text" class="validate">
          <label for="description">Description</label>
        </div>
      </div>
      <div class="row">
        <div class="input-field col s6">
          <input name="received_amount" id="received_amount" <?php echo 'value="'.$cashdesk->received_amount.'"'; ?> type="text" required class="validate">
          <label for="received_amount">Montant initial</label>
        </div>
      </div>
    <button class="btn waves-effect waves-light" type="submit" name="action">Modifier
    <i class="material-icons right">edit</i>
    </button>
    </form>
  </div>
        