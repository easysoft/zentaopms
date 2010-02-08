<script src='<?php echo $jsRoot;?>jquery/reverseorder/raw.js' type='text/javascript'></script>
<fieldset>
  <legend onclick='$("#historyItem li").reverseOrder();' class='hand'><?php echo $lang->history . $lang->reverse;?></legend>
  <ol id='historyItem'>
    <?php $i = 1;?>
    <?php foreach($actions as $action):?>
    <li value='<?php echo $i ++;?>'>
      <?php
      if(isset($users[$action->actor])) $action->actor = $users[$action->actor];
      if(strpos($action->actor, ':') !== false) $action->actor = substr($action->actor, strpos($action->actor, ':') + 1);
      if($action->objectType == 'bug'   and $action->action == 'Resolved') $action->action .= " as $action->extra";
      if($action->objectType == 'story' and $action->action == 'Reviewed') $action->action .= " as $action->extra";
      if($action->objectType == 'story' and $action->action == 'Closed')   $action->action .= " for $action->extra";
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
