<div style='margin: 0 auto; max-width: 300px'>
  <h6><?php echo $lang->project->afterInfo;?></h6>
  <div class='list-group mg-0'>
    <?php echo html::a($this->createLink('project', 'team', "projectID=$projectID"), $lang->project->setTeam . " <i class='icon-chevron-right pull-right'></i>", '', "class='list-group-item'");?>
    <?php echo html::a($this->createLink('project', 'linkstory', "projectID=$projectID"), $lang->project->linkStory . " <i class='icon-chevron-right pull-right'></i>", '', "class='list-group-item'");?>
    <?php echo html::a($this->createLink('task', 'create', "project=$projectID"), $lang->project->createTask . " <i class='icon-chevron-right pull-right'></i>", '', "class='list-group-item'");?>
    <?php echo html::a($this->createLink('project', 'task', "projectID=$projectID"), $lang->project->goback . " <i class='icon-chevron-right pull-right'></i>", '', "class='list-group-item'");?>
  </div>
</div>
