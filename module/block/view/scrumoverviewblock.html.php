<?php
/**
 * The scrum overview block view file of block module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     block
 * @version     $Id$
 * @link        https://www.zentao.net
 */
?>
<?php $vision = $this->config->vision;?>
<?php if(empty($totalData)): ?>
<div class='empty-tip'><?php common::printLink('project', 'create', '', "<i class='icon-plus'></i> " . $lang->project->create, '', "class='btn btn-primary'")?></div>
<?php else:?>
<table class="table table-data">
  <thead>
    <tr>
      <th><?php echo $lang->block->storyCount;?></th>
      <th></th>
      <th><?php echo $lang->block->investment;?></th>
      <th></th>
      <th><?php echo $lang->block->taskCount;?></th>
      <th></th>
      <?php if($vision == 'rnd'):?>
      <th><?php echo $lang->block->bugCount;?></th>
      <?php endif;?>
      <th></th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <th><?php echo $lang->block->totalStory . ':';?></th>
      <td><?php echo $totalData[$projectID]->allStories;?></td>
      <th><?php echo $lang->block->totalPeople . ':';?></th>
      <td><?php echo $totalData[$projectID]->teamCount ? html::a($this->createLink('project', 'team', 'projectID=' . $projectID), $totalData[$projectID]->teamCount) : 0;?></td>
      <th><?php echo ($vision == 'rnd' ? $lang->block->wait : $lang->block->totalTask) . ':';?></th>
      <td><?php echo $vision == 'rnd' ? $totalData[$projectID]->waitTasks : $totalData[$projectID]->allTasks;?></td>
      <?php if($vision == 'rnd'):?>
      <th><?php echo $lang->block->totalBug . ':';?></th>
      <td><?php echo $totalData[$projectID]->allBugs;?></td>
      <?php endif;?>
    </tr>
    <tr>
      <th><?php echo $lang->block->done . ':';?></th>
      <td><?php echo $totalData[$projectID]->doneStories;?></td>
      <th><?php echo $lang->block->estimate . ':';?></th>
      <td><?php echo $totalData[$projectID]->estimate . $lang->execution->workHourUnit;?></td>
      <th><?php echo ($vision == 'rnd' ? $lang->block->doing : $lang->block->done) . ':';?></th>
      <td><?php echo $vision == 'rnd' ? $totalData[$projectID]->doingTasks: $totalData[$projectID]->liteDoneTasks;?></td>
      <?php if($vision == 'rnd'):?>
      <th><?php echo $lang->bug->statusList['resolved'] . ':';?></th>
      <td><?php echo $totalData[$projectID]->doneBugs;?></td>
      <?php endif;?>
    </tr>
    <tr>
      <th><?php echo $lang->block->left . ':';?></th>
      <td><?php echo $totalData[$projectID]->leftStories;?></td>
      <th><?php echo $lang->block->consumedHours . ':';?></th>
      <td><?php echo $totalData[$projectID]->consumed . $lang->execution->workHourUnit;?></td>
      <th><?php echo $vision == 'rnd' ? $lang->block->done . ':' : $lang->block->undone;?></th>
      <td><?php echo $vision == 'rnd' ? $totalData[$projectID]->rndDoneTasks : $totalData[$projectID]->leftTasks;?></td>
      <?php if($vision == 'rnd'):?>
      <th><?php echo $lang->bug->unResolved . ':';?></th>
      <td><?php echo $totalData[$projectID]->leftBugs;?></td>
      <?php endif;?>
    </tr>

  </tbody>
</table>

<?php endif;?>
