<?php
/**
 * The scrum overview block view file of block module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     block
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php if(empty($totalData)): ?>
<div class='empty-tip'><?php common::printLink('project', 'create', '', "<i class='icon-plus'></i> " . $lang->project->create, '', "class='btn btn-primary'")?></div>
<?php else:?>
<table class="table table-data">
  <thead>
    <tr>
      <th><?php echo $lang->block->storyNum;?></th>
      <th></th>
      <th><?php echo $lang->personnel->invest;?></th>
      <th></th>
      <th><?php echo $lang->workingHour;?></th>
      <th></th>
      <th><?php echo $lang->block->bugNum;?></th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <th><?php echo $lang->block->totalStory . ':';?></th>
      <td><?php echo $totalData[$projectID]->allStories;?></td>
      <th><?php echo $lang->block->totalPeople . ':';?></th>
      <td><?php echo  $totalData[$projectID]->teamCount ? html::a($this->createLink('project', 'manageMembers', 'projectID=' . $projectID), $totalData[$projectID]->teamCount) : 0;?></td>
      <th><?php echo $lang->block->estimatedHours . ':';?></th>
      <td><?php echo $totalData[$projectID]->estimate . $lang->execution->workHour;?></td>
      <th><?php echo $lang->block->totalBug. ':';?></th>
      <td><?php echo $totalData[$projectID]->allBugs;?></td>
    </tr>
    <tr>
      <th><?php echo $lang->block->done . ':';?></th>
      <td><?php echo $totalData[$projectID]->doneStories;?></td>
      <th></th>
      <td></td>
      <th><?php echo $lang->block->consumedHours . ':';?></th>
      <td><?php echo $totalData[$projectID]->consumed . $lang->execution->workHour;?></td>
      <th><?php echo $lang->block->done . ':';?></th>
      <td><?php echo $totalData[$projectID]->doneBugs;?></td>
    </tr>
    <tr>
      <th><?php echo $lang->block->left . ':';?></th>
      <td><?php echo $totalData[$projectID]->leftStories;?></td>
      <th></th>
      <td></td>
      <th></th>
      <td></td>
      <th><?php echo $lang->bug->unResolved . ':';?></th>
      <td><?php echo $totalData[$projectID]->leftBugs;?></td>
    </tr>

  </tbody>
</table>

<?php endif;?>
