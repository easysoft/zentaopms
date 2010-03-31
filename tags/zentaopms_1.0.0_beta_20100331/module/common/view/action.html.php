<script src='<?php echo $jsRoot;?>jquery/reverseorder/raw.js' type='text/javascript'></script>
<div>
<fieldset>
  <legend onclick='$("#historyItem li").reverseOrder();' class='hand'><?php echo $lang->history . $lang->reverse;?></legend>
  <ol id='historyItem'>
    <?php $i = 1; ?>
    <?php foreach($actions as $action):?>
    <li value='<?php echo $i ++;?>'>
      <?php
      if(isset($users[$action->actor])) $action->actor = $users[$action->actor];
      if(strpos($action->actor, ':') !== false) $action->actor = substr($action->actor, strpos($action->actor, ':') + 1);
      ?>
      <span><?php $this->action->printAction($action);?>
      <?php if(!empty($action->comment) or !empty($action->history)):?>
      <div class='history'>
        <div id='changes'><?php echo $this->action->printChanges($action->objectType, $action->history);?></div>
        <?php if($action->comment and $action->history) echo '<br />'; echo nl2br($action->comment);?>
      </div>
      <?php endif;?>
    </li>
    <?php endforeach;?>
  </ol>
</fieldset>
</div>
