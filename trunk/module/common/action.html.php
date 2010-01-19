<fieldset>
  <legend><?php echo $lang->history;?></legend>
  <ol>
    <?php foreach($actions as $action):?>
    <li>
      <?php
      if(isset($users[$action->actor])) $action->actor = $users[$action->actor];
      if($pos = strpos($action->actor, ':') !== false) $action->actor = substr($action->actor, $pos + 1);
      ?>
      <span><?php echo "$action->date, <strong>$action->action</strong> by <strong>$action->actor</strong>"; ?></span>
      <?php if(!empty($action->comment) or !empty($action->history)):?>
      <div class='history'>
      <?php
      foreach($action->history as $history)
      {
          if($history->diff != '')
          {
              echo "CHANGE <strong>$history->field</strong>, the diff is: <blockquote>" . nl2br($history->diff) . "</blockquote>";
          }
          else
          {
              echo "CHANGE <strong>$history->field</strong> FROM '$history->old' TO '$history->new' . <br />";
          }
      }
      echo nl2br($action->comment); 
      ?>
      </div>
      <?php endif;?>
    </li>
    <?php endforeach;?>
  </ol>
</fieldset>
