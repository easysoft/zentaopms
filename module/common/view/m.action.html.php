<div id='actionbox'>
<fieldset>
  <hr color='#dddddd'/>

  <ol id='historyItem' style='padding-left:20px; font-size:11px'>
    <?php $i = 1; ?>
    <?php foreach($actions as $action):?>
    <li value='<?php echo $i ++;?>'>
      <?php
      if(isset($users[$action->actor])) $action->actor = $users[$action->actor];
      if($action->action == 'assigned' and isset($users[$action->extra]) ) $action->extra = $users[$action->extra];
      if(strpos($action->actor, ':') !== false) $action->actor = substr($action->actor, strpos($action->actor, ':') + 1);
      ?>
      <span><?php $this->action->printAction($action);?></span>
      <?php if(!empty($action->comment) or !empty($action->history)):?>
      <?php if(!empty($action->comment)) echo "<div class='history'>";?>
      <?php if($action->comment) echo strip_tags($action->comment) == $action->comment ? nl2br($action->comment) : $action->comment; ?>
      <?php if(!empty($action->comment)) echo "</div>";?>
      <?php endif;?>
    </li>
    <?php endforeach;?>
  </ol>

</fieldset>
</div>
