<script src='<?php echo $jsRoot;?>jquery/reverseorder/raw.js' type='text/javascript'></script>
<?php if(!isset($actionTheme)) $actionTheme = 'fieldset';?>
<?php if($actionTheme == 'fieldset'):?>
<div>
<fieldset>
  <legend onclick='$("#historyItem li").reverseOrder();' class='hand'><?php echo $lang->history . $lang->reverse;?></legend>
<?php else:?>
<table class='table-1'>
  <caption onclick='$("#historyItem li").reverseOrder();' class='hand'><?php echo $lang->history . $lang->reverse;?></caption>
  <tr><td>
<?php endif;?>

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

<?php if($actionTheme == 'fieldset'):?>
</fieldset>
<?php else:?>
</td></tr></table>
<?php endif;?>
</div>
