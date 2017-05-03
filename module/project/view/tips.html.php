<div style='margin: 0 auto; max-width: 400px'>
  <h6><?php echo $lang->project->afterInfo;?></h6>
  <div class='pdb-20'>
    <?php echo html::a($this->createLink('project', 'team', "projectID=$projectID"), $lang->project->setTeam, '', "class='btn'");?>
    <?php if($this->config->global->flow != 'onlyTask') echo html::a($this->createLink('project', 'linkstory', "projectID=$projectID"), $lang->project->linkStory, '', "class='btn'");?>
    <?php echo html::a($this->createLink('task', 'create', "project=$projectID"), $lang->project->createTask, '', "class='btn'");?>
    <?php echo html::a($this->createLink('project', 'task', "projectID=$projectID"), $lang->project->goback, '', "class='btn'");?>
  </div>
</div>
