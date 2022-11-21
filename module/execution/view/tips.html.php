<div style='margin: 0 auto; max-width: <?php echo $this->app->getClientLang() == 'zh-cn' ? '500px' : '580px';?>'>
  <p><strong><?php echo $lang->execution->afterInfo;?></strong></p>
  <div>
    <?php echo html::a($this->createLink('execution', 'team', "executionID=$executionID"), $lang->execution->setTeam, '', "class='btn' data-app='execution'");?>
    <?php if($execution->lifetime != 'ops') echo html::a($this->createLink('execution', 'linkstory', "executionID=$executionID"), $lang->execution->linkStory, '', "class='btn' data-app='execution'");?>
    <?php echo html::a($this->createLink('task', 'create', "execution=$executionID"), $lang->execution->createTask, '', "class='btn' data-app='execution'");?>
    <?php echo html::a($this->createLink('execution', 'task', "executionID=$executionID"), $lang->execution->goback, '', "class='btn' data-app='execution'");?>
    <?php echo html::a('javascript:void(0)', $lang->execution->gobackExecution, '', "class='btn' onclick='closeTipsModal($projectID)' data-app='project'");?>
  </div>
</div>
<script>
/**
 * Click the return to iteration list button to close tipModal.
 *
 * @param  int $projectID
 * @access public
 * @return void
 */
function closeTipsModal(projectID)
{
    $.apps.close('execution');
    var link = createLink('project', 'execution', 'status=all&projectID=' + projectID);
    $.apps.open(link, 'project');
}
</script>
