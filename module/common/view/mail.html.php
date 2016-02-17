<?php if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}?>
<?php if(isset($users[$action->actor])) $action->actor = $users[$action->actor];?>
<?php if(isset($users[$action->extra])) $action->extra = $users[$action->extra];?>
<span><?php $this->action->printAction($action);?></span>
<?php if(!empty($action->comment) or !empty($action->history)):?>
<div class='history'>
<div><?php echo $this->action->printChanges($action->objectType, $action->history);?></div>
<?php if($action->comment and $action->history) echo '<br />'; echo $action->comment;?>
</div>
<?php endif;?>
<?php include dirname(__FILE__) . '/mail.css.php'?>
