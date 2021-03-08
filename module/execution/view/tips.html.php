<div style='margin: 0 auto; max-width: 400px'>
  <p><strong><?php echo $lang->execution->afterInfo;?></strong></p>
  <div>
    <?php echo html::a($this->createLink('execution', 'team', "executionID=$executionID"), $lang->execution->setTeam, '', "class='btn'");?>
    <?php if($execution->type != 'ops') echo html::a($this->createLink('execution', 'linkstory', "executionID=$executionID"), $lang->execution->linkStory, '', "class='btn'");?>
    <?php echo html::a($this->createLink('task', 'create', "execution=$executionID"), $lang->execution->createTask, '', "class='btn'");?>
    <?php echo html::a($this->createLink('execution', 'task', "executionID=$executionID"), $lang->execution->goback, '', "class='btn'");?>
  </div>
</div>
