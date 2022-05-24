<?php $width = $this->app->getClientLang() == 'en' ? '580px' : '500px';?>
<?php echo "<div style='margin: 0 auto; max-width: $width'";?>
  <p><strong><?php echo $lang->execution->afterInfo;?></strong></p>
  <div>
    <?php echo html::a($this->createLink('execution', 'team', "executionID=$executionID"), $lang->execution->setTeam, '', "class='btn' data-app='execution'");?>
    <?php if($execution->lifetime != 'ops') echo html::a($this->createLink('execution', 'linkstory', "executionID=$executionID"), $lang->execution->linkStory, '', "class='btn' data-app='execution'");?>
    <?php echo html::a($this->createLink('task', 'create', "execution=$executionID"), $lang->execution->createTask, '', "class='btn' data-app='execution'");?>
    <?php echo html::a($this->createLink('execution', 'task', "executionID=$executionID"), $lang->execution->goback, '', "class='btn' data-app='execution'");?>
    <?php echo html::a($this->createLink('project', 'execution', "status=all&projectID=$projectID"), $lang->execution->gobackExecution, '', "class='btn' data-app='project'");?>
  </div>
</div>
